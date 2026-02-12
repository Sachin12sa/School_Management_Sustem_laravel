<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
    'name',
    'last_name',
    'email',
    'password',
    'user_type',
    'admission_number',
    'roll_number',
    'class_id',
    'gender',
    'date_of_birth',
    'mobile_number',
    'admission_date',
    'profile_pic',
    'blood_group',
    'height',
    'weight',
    'is_delete',
];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    static public function getEmailSingle($email){
        return User::where('email','=',$email)->first();
    }
     static public function getTokenSingle($remember_token){
        return User::where('remember_token','=',$remember_token)->first();
    }
    // Function to get Admin
     static public function getAdmin(){
        $return= User::select('users.*')
                            ->where('user_type','=',1)
                            ->where('is_delete','=',0);
                            if (request('name')) {
                                    $return->where('name', 'like', '%' . request('name') . '%');
                                }
                            if (request('email')) {
                                    $return->where('email', 'like', '%' . request('email') . '%');
                                }
                                if (request('date')) {
                                    $return->whereDate('created_at', 'like', '%' . request('date') . '%');
                                }

                           $return = $return->orderBy('id','desc')
                            ->paginate(10);
                        return $return;
    }
    // Function to get Parent
    static public function getParent(){
        $return= User::select('users.*')
                            ->where('user_type','=',4)
                            ->where('is_delete','=',0);
                            if (request('name')) {
                                    $return->where('name', 'like', '%' . request('name') . '%');
                                }
                            if (request('email')) {
                                    $return->where('email', 'like', '%' . request('email') . '%');
                                }
                             if (request('mobile_number')) {
                                    $return->where('mobile_number', 'like', '%' . request('mobile_number') . '%');
                                }

                           $return = $return->orderBy('id','desc')
                            ->paginate(10);
                        return $return;
    }
        // Function to get student
    static public function getStudent(){

       $return = User::select('users.*', 'classes.name as class_name','parent.name as parent_name','parent.last_name as parent_last_name')
                            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                            ->leftJoin('users as parent', 'parent.id', '=', 'users.parent_id')
                            ->where('users.user_type','=',3)
                            ->where('users.is_delete','=',0);
                                if (request('name')) {
                                        $return->where('users.name', 'like', '%' . request('name') . '%');
                                    }

                                if (request('email')) {
                                        $return->where('users.email', 'like', '%' . request('email') . '%');
                                    }
                                if (request('admission_number')) {
                                    $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
                                }
                             
                             
                           $return = $return->orderBy('id','desc')
                            ->paginate(10);
                        return $return;
    }
    // function to get teacher
        static public function getTeacher(){

       $return = User::select('users.*', 'classes.name as class_name','parent.name as parent_name','parent.last_name as parent_last_name')
                            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                            ->leftJoin('users as parent', 'parent.id', '=', 'users.parent_id')
                            ->where('users.user_type','=',2)
                            ->where('users.is_delete','=',0);
                                if (request('name')) {
                                        $return->where('users.name', 'like', '%' . request('name') . '%');
                                    }

                                if (request('email')) {
                                        $return->where('users.email', 'like', '%' . request('email') . '%');
                                    }
                                if (request('admission_number')) {
                                    $return->where('users.admission_number', 'like', '%' . request('admission_number') . '%');
                                }
                             
                             
                           $return = $return->orderBy('id','desc')
                            ->paginate(10);
                        return $return;
    }
    // function to get teacher for classassign
    static public function getTeacherClass(){

       $return = User::select('users.*', 'classes.name as class_name','parent.name as parent_name','parent.last_name as parent_last_name')
                            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                            ->leftJoin('users as parent', 'parent.id', '=', 'users.parent_id')
                            ->where('users.user_type','=',2)
                            ->where('users.is_delete','=',0)
                            ->orderBy('id','desc')
                            ->get();
                        return $return;
    }
    // function for get assign student 
  public static function getSearchStudent()
        {
            if (
                !empty(request('student_id')) ||
                !empty(request('name')) ||
                !empty(request('last_name')) ||
                !empty(request('email'))
            ) {
                $return =User::select('users.*', 'classes.name as class_name', 'parent.name as parent_name')
                            ->leftJoin('users as parent', 'parent.id', '=', 'users.parent_id')
                            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                            ->where('users.user_type', 3)
                            ->where('users.is_delete', 0);

                    if (request('student_id')) {
                        $return->where('users.id', 'like', '%' . request('student_id') . '%');
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

                    return $return->orderBy('users.id', 'desc')
                                ->limit(50)
                                ->get();
            }

            return collect(); // always return something
        }

    static public function getMyStudent($parent_id){
        $return =User::select('users.*', 'classes.name as class_name', 'parent.name as parent_name')
                            ->leftJoin('users as parent', 'parent.id', '=', 'users.parent_id')
                            ->leftJoin('classes', 'classes.id', '=', 'users.class_id')
                            ->where('users.user_type', 3)
                            ->where('users.is_delete', 0)
                            ->where('users.parent_id', '=',$parent_id)
                            ->orderBy('users.id', 'desc')
                            ->limit(50)
                            ->get();
                            return $return;
            }
    

    
    static public function getSingle($id){
        return User::find($id);
    }

    public function getProfile()
            {
                if (!empty($this->profile_pic) && file_exists(storage_path('app/public/' . $this->profile_pic))) {
                    return asset('storage/' . $this->profile_pic);
                } else {
                    return ""; // or return a default avatar
                }
            }


}
