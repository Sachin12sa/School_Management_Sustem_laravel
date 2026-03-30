<?php

namespace App\Models;

use App\Helpers\NepaliCalendar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*──────────────────────────────────────────────────────────────────────────
     | MASS-ASSIGNABLE FIELDS
     ──────────────────────────────────────────────────────────────────────────*/
    protected $fillable = [
        'name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'admission_number',
        'roll_number',
        'class_id',
        'section_id',        // ✅ class section
        'session_id',        // ✅ academic year — e.g. 2081, 2082
        'promotion_status',  // ✅ pending / promoted / failed / graduated
        'gender',
        'date_of_birth',
        'date_of_joining',
        'admission_date',
        'mobile_number',
        'blood_group',
        'height',
        'weight',
        'religion',
        'occupation',
        'marital_status',
        'current_address',
        'permanent_address',
        'address',
        'qualification',
        'work_experience',
        'profile_pic',
        'status',
        'is_delete',
        'parent_id',
    ];

    /*──────────────────────────────────────────────────────────────────────────
     | HIDDEN
     ──────────────────────────────────────────────────────────────────────────*/
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*──────────────────────────────────────────────────────────────────────────
     | CASTS
     ──────────────────────────────────────────────────────────────────────────*/
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /*──────────────────────────────────────────────────────────────────────────
     | RELATIONSHIPS
     ──────────────────────────────────────────────────────────────────────────*/

    /** Student → many Parents (pivot: student_parent) */
    public function parents()
    {
        return $this->belongsToMany(User::class, 'student_parent', 'student_id', 'parent_id');
    }

    /** Parent → many Students (pivot: student_parent) */
    public function students()
    {
        return $this->belongsToMany(User::class, 'student_parent', 'parent_id', 'student_id');
    }

    /** Student → Section */
    public function section()
    {
        return $this->belongsTo(ClassSectionModel::class, 'section_id');
    }

    /** Student / any user → Academic Session */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSessionModel::class, 'session_id');
    }

    /*──────────────────────────────────────────────────────────────────────────
     | BOOT
     | • Auto-assign current academic session on student creation
     | • Auto-generate admission number if not provided
     ──────────────────────────────────────────────────────────────────────────*/
    protected static function booted()
    {
        static::creating(function ($user) {
            // Only applies to students
            if ($user->user_type != 3) {
                return;
            }

            // ── 1. Auto-assign current session ──────────────────────────────
            if (empty($user->session_id)) {
                $currentSession   = AcademicSessionModel::getCurrent();
                $user->session_id = $currentSession?->id;
            }

            // ── 2. Auto-generate admission number ───────────────────────────
            // Skip if already set (e.g. promotion controller sets it explicitly)
            if (empty($user->admission_number)) {
                $currentYear = NepaliCalendar::currentYear();

                $lastStudent = self::where('user_type', 3)
                    ->where('admission_number', 'like', "ADM-$currentYear-%")
                    ->orderBy('id', 'desc')
                    ->first();

                $nextId = 1;
                if ($lastStudent) {
                    $parts  = explode('-', $lastStudent->admission_number);
                    $nextId = (int) end($parts) + 1;
                }

                $user->admission_number = 'ADM-' . $currentYear . '-'
                    . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    /*──────────────────────────────────────────────────────────────────────────
     | INSTANCE HELPERS
     ──────────────────────────────────────────────────────────────────────────*/

    /**
     * Returns the URL to the user's profile picture.
     * Falls back to a default avatar so views never get a broken image.
     */
    public function getProfile(): string
    {
        if (
            !empty($this->profile_pic) &&
            file_exists(storage_path('app/public/' . $this->profile_pic))
        ) {
            return asset('storage/' . $this->profile_pic);
        }

        return asset('dist/assets/img/user.jpg');
    }

    public function getAttendance($student_id, $class_id, $attendance_date)
    {
        return \App\Models\StudentAttendanceModel::CheckAlreadyAttendance(
            $student_id,
            $class_id,
            $attendance_date
        );
    }

    /*──────────────────────────────────────────────────────────────────────────
     | PRIVATE HELPER — resolve which session to filter by
     |
     | Priority:
     |   1. ?session_id=X in the request URL  (admin browsing old year)
     |   2. Current active session            (default — current year)
     |   3. null                              (no session set up yet — show all)
     ──────────────────────────────────────────────────────────────────────────*/
    private static function resolveSessionFilter(): ?int
    {
        if (request('session_id')) {
            return (int) request('session_id');
        }

        return AcademicSessionModel::getCurrent()?->id;
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — SINGLE LOOKUPS
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getSingle($id)
    {
        return User::find($id);
    }

    static public function getEmailSingle($email)
    {
        return User::where('email', $email)->first();
    }

    static public function getTokenSingle($remember_token)
    {
        return User::where('remember_token', $remember_token)->first();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — ADMIN
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getAdmin()
    {
        $return = User::select('users.*')
            ->where('user_type', 1)
            ->where('is_delete', 0);

        if (request('name')) {
            $return->where('name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('email', 'like', '%' . request('email') . '%');
        }
        if (request('date')) {
            $return->whereDate('created_at', request('date'));
        }

        return $return->orderBy('id', 'desc')->paginate(10);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — ACCOUNTANT
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getAccountant()
    {
        $return = User::select('users.*')
            ->where('user_type', 5)
            ->where('is_delete', 0);

        if (request('name')) {
            $return->where('name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('email', 'like', '%' . request('email') . '%');
        }
        if (request('date')) {
            $return->whereDate('created_at', request('date'));
        }

        return $return->orderBy('id', 'desc')->paginate(10);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — PARENT
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getParent()
    {
        $return = User::select('users.*')
            ->where('user_type', 4)
            ->where('is_delete', 0);

        if (request('name')) {
            $return->where('name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('email', 'like', '%' . request('email') . '%');
        }
        if (request('mobile_number')) {
            $return->where('mobile_number', 'like', '%' . request('mobile_number') . '%');
        }

        return $return->orderBy('id', 'desc')->paginate(10);
    }

    /** Flat list for student ↔ parent assignment dropdowns. */
    static public function getParentListToStudent()
    {
        $return = User::select('users.*')
            ->where('user_type', 4)
            ->where('is_delete', 0);

        if (request('name')) {
            $return->where('name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('email', 'like', '%' . request('email') . '%');
        }
        if (request('mobile_number')) {
            $return->where('mobile_number', 'like', '%' . request('mobile_number') . '%');
        }

        return $return->orderBy('id', 'desc')->get();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — STUDENT — Main paginated list
     |
     | Changes from old version:
     |   ✅ Filters by current academic session by default
     |   ✅ Admin can browse old sessions via ?session_id=X
     |   ✅ Joins session table to show session_name in list
     |   ✅ Removed broken parent leftJoin (caused duplicates with pivot table)
     |   ✅ Added class_id + section_id filters
     |   ✅ Parents loaded via Eloquent relationship per row — no duplication
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getStudent()
    {
        $sessionId = self::resolveSessionFilter();

        $return = User::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name',
                'academic_sessions.name as session_name'
            )
            ->leftJoin('classes',           'classes.id',           '=', 'users.class_id')
            ->leftJoin('class_sections',    'class_sections.id',    '=', 'users.section_id')
            ->leftJoin('academic_sessions', 'academic_sessions.id', '=', 'users.session_id')
            ->where('users.user_type', 3)
            ->where('users.is_delete',  0);

        // Session filter — always active (shows current year unless overridden)
        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }

        if (request('name')) {
            $return->where('users.name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('users.email', 'like', '%' . request('email') . '%');
        }
        if (request('admission_number')) {
            $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
        }
        if (request('class_id')) {
            $return->where('users.class_id', request('class_id'));
        }
        if (request('section_id')) {
            $return->where('users.section_id', request('section_id'));
        }

        return $return->orderBy('users.id', 'desc')->paginate(10);
    }
    static public function getStudents()
    {
        $sessionId = self::resolveSessionFilter();

        $return = User::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name',
                'academic_sessions.name as session_name'
            )
            ->leftJoin('classes',           'classes.id',           '=', 'users.class_id')
            ->leftJoin('class_sections',    'class_sections.id',    '=', 'users.section_id')
            ->leftJoin('academic_sessions', 'academic_sessions.id', '=', 'users.session_id')
            ->where('users.user_type', 3)
            ->where('users.is_delete',  0);

        // Session filter — always active (shows current year unless overridden)
        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }

        if (request('name')) {
            $return->where('users.name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('users.email', 'like', '%' . request('email') . '%');
        }
        if (request('admission_number')) {
            $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
        }
        if (request('class_id')) {
            $return->where('users.class_id', request('class_id'));
        }
        if (request('section_id')) {
            $return->where('users.section_id', request('section_id'));
        }

        return $return->orderBy('users.id', 'desc')->get();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — STUDENT — Simple list for marks / attendance (no pagination)
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getStudentClass($class_id)
    {
        $sessionId = self::resolveSessionFilter();

        $return = self::select(
                'users.id',
                'users.name',
                'users.last_name',
                'users.roll_number',
                'users.admission_number'
            )
            ->where('users.user_type', 3)
            ->where('users.class_id',  $class_id)
            ->where('users.is_delete', 0);

        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }

        return $return->orderBy('users.roll_number', 'asc')
                      ->orderBy('users.name', 'asc')
                      ->get();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — STUDENT — Per class (paginated, class detail view)
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getStudentPerClass($class_id)
    {
        $sessionId = self::resolveSessionFilter();

        $return = self::select(
                'users.*',
                'class_sections.name as section_name',
                // Correlated subquery — 1 row per student, no pivot join duplicates
                DB::raw('(SELECT u2.name FROM users u2
                          INNER JOIN student_parent sp ON sp.parent_id = u2.id
                          WHERE sp.student_id = users.id
                          ORDER BY sp.id ASC LIMIT 1) as parent_name'),
                DB::raw('(SELECT u2.last_name FROM users u2
                          INNER JOIN student_parent sp ON sp.parent_id = u2.id
                          WHERE sp.student_id = users.id
                          ORDER BY sp.id ASC LIMIT 1) as parent_last_name')
            )
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->where('users.class_id',  $class_id)
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0);

        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }
        if (request('name')) {
            $return->where('users.name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('users.email', 'like', '%' . request('email') . '%');
        }
        if (request('admission_number')) {
            $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
        }
        if (request('section_id')) {
            $return->where('users.section_id', request('section_id'));
        }

        return $return->orderBy('users.id', 'desc')->paginate(10);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — STUDENT — Per section (paginated, section detail view)
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getStudentBySection($section_id)
    {
        $sessionId = self::resolveSessionFilter();

        $return = self::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name',
                DB::raw('(SELECT u2.name FROM users u2
                          INNER JOIN student_parent sp ON sp.parent_id = u2.id
                          WHERE sp.student_id = users.id
                          ORDER BY sp.id ASC LIMIT 1) as parent_name'),
                DB::raw('(SELECT u2.last_name FROM users u2
                          INNER JOIN student_parent sp ON sp.parent_id = u2.id
                          WHERE sp.student_id = users.id
                          ORDER BY sp.id ASC LIMIT 1) as parent_last_name')
            )
            ->leftJoin('classes',        'classes.id',        '=', 'users.class_id')
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->where('users.section_id', $section_id)
            ->where('users.user_type',  3)
            ->where('users.is_delete',  0);

        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }
        if (request('name')) {
            $return->where('users.name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('users.email', 'like', '%' . request('email') . '%');
        }
        if (request('admission_number')) {
            $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
        }

        return $return->orderBy('users.id', 'desc')->paginate(10);
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — STUDENT — Parent portal (my children)
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getMyStudent($parent_id)
    {
        $sessionId = self::resolveSessionFilter();

        $return = User::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name'
            )
            ->leftJoin('classes',        'classes.id',        '=', 'users.class_id')
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->join('student_parent', 'student_parent.student_id', '=', 'users.id')
            ->where('student_parent.parent_id', $parent_id)
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0);

        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }

        return $return->orderBy('users.id', 'desc')->limit(50)->get();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — STUDENT — Global search (marks register / fee assignment)
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getSearchStudent()
    {
        if (
            empty(request('student_id')) &&
            empty(request('name'))       &&
            empty(request('last_name'))  &&
            empty(request('email'))
        ) {
            return collect();
        }

        $sessionId = self::resolveSessionFilter();

        $return = User::select(
                'users.*',
                'classes.name as class_name',
                'class_sections.name as section_name'
            )
            ->leftJoin('classes',        'classes.id',        '=', 'users.class_id')
            ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')
            ->where('users.user_type', 3)
            ->where('users.is_delete', 0);

        if ($sessionId) {
            $return->where('users.session_id', $sessionId);
        }
        if (request('student_id')) {
            $return->where('users.id', request('student_id'));
        }
        if (request('name')) {
            $return->whereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower(request('name')) . '%']);
        }
        if (request('last_name')) {
            $return->where('users.last_name', 'like', '%' . request('last_name') . '%');
        }
        if (request('email')) {
            $return->where('users.email', 'like', '%' . request('email') . '%');
        }

        return $return->orderBy('users.id', 'desc')->limit(50)->get();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — TEACHER
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getTeacher()
    {
        $return = User::select('users.*', 'classes.name as class_name')
            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
            ->where('users.user_type', 2)
            ->where('users.is_delete', 0);

        if (request('name')) {
            $return->where('users.name', 'like', '%' . request('name') . '%');
        }
        if (request('email')) {
            $return->where('users.email', 'like', '%' . request('email') . '%');
        }

        return $return->orderBy('users.id', 'desc')->paginate(10);
    }

    /** Students in the same class as a teacher — for teacher portal. */
    static public function getTeacherStudent($teacher_id)
        {
            $sessionId = self::resolveSessionFilter();

            $return = User::select(
                        'users.*',
                        'classes.name as class_name',
                        'class_sections.name as section_name'
                    )
                    ->join('assign_class_teachers as act', function ($join) use ($teacher_id) {
                        $join->on('act.class_id', '=', 'users.class_id')
                            ->on('act.section_id', '=', 'users.section_id')
                            ->where('act.teacher_id', '=', $teacher_id)
                            ->where('act.is_delete', '=', 0);
                    })
                    ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                    ->leftJoin('class_sections', 'class_sections.id', '=', 'users.section_id')

                    ->where('users.user_type', 3) // students
                    ->where('users.is_delete', 0);

            if ($sessionId) {
                $return->where('users.session_id', $sessionId);
            }

    return $return->orderBy('users.id', 'desc')->paginate(10000);
}
    /** All teachers for class-teacher assignment — grouped to avoid duplicates. */
    static public function getTeacherClass()
    {
        return User::select('users.*', 'classes.name as class_name')
            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
            ->where('users.user_type', 2)
            ->where('users.is_delete', 0)
            ->orderBy('users.id', 'desc')
            ->groupBy('users.id')
            ->get();
    }

    /*──────────────────────────────────────────────────────────────────────────
     | STATIC — GENERAL
     ──────────────────────────────────────────────────────────────────────────*/

    static public function getUser($user_type)
    {
        return self::select('users.*')
            ->where('users.user_type', $user_type)
            ->where('users.is_delete', 0)
            ->get();
    }

    static public function getTotalUser($user_type)
    {
        return self::where('user_type', $user_type)
            ->where('is_delete', 0)
            ->count();
    }

    static public function SearchUser($search)
    {
        return self::select('users.*')
            ->where(function ($query) use ($search) {
                $query->where('users.name',      'like', '%' . $search . '%')
                      ->orWhere('users.last_name', 'like', '%' . $search . '%');
            })
            ->limit(10)
            ->get();
    }
}