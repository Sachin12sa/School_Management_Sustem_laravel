<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ChatModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Main chat view
     * Route: GET /admin/chat (or teacher/chat, student/chat, etc.)
     */
    public function chat(Request $request)
    {
        $data['header_title'] = 'My Chat';

        $sender_id   = Auth::user()->id;
        $receiver_id = $request->receiver_id;

        $data['receiver_id'] = $receiver_id;

        // Fetch receiver info if receiver_id is provided
        $data['getReceiver'] = null;
        if (!empty($receiver_id)) {
            $data['getReceiver'] = User::getSingle($receiver_id);

            // Mark all incoming messages from this user as read
            ChatModel::where('sender_id', $receiver_id)
                ->where('receiver_id', $sender_id)
                ->update(['status' => 1]);
        }

        // Fetch chat messages between current user and receiver
        $data['getChat'] = [];
        if (!empty($receiver_id)) {
            $data['getChat'] = ChatModel::with(['sender', 'receiver'])
                ->where(function ($q) use ($sender_id, $receiver_id) {
                    $q->where('sender_id', $sender_id)
                      ->where('receiver_id', $receiver_id);
                })
                ->orWhere(function ($q) use ($sender_id, $receiver_id) {
                    $q->where('sender_id', $receiver_id)
                      ->where('receiver_id', $sender_id);
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        // Sidebar: unique contacts with their latest message
        $data['getChatUsers'] = $this->getChatUsers($sender_id);

        return view('chat.index', $data);
    }

    /**
     * Send a new message (AJAX)
     * Route: POST /admin/chat/submit
     */
    public function submitChat(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'nullable|string|max:5000',
            'file_name'   => 'nullable|file|max:10240', // 10 MB max
        ]);

        // Must have message or file
        if (empty($request->message) && empty($request->file('file_name'))) {
            return response()->json(['status' => false, 'message' => 'Message or file required.'], 422);
        }

        $chat              = new ChatModel;
        $chat->sender_id   = Auth::user()->id;
        $chat->receiver_id = $request->receiver_id;
        $chat->message     = $request->message ?? '';
        $chat->status      = 0;

        if ($request->hasFile('file_name')) {
            $file      = $request->file('file_name');
            $ext       = $file->getClientOriginalExtension();
            $file_name = Str::random(20) . '.' . $ext;
            $file->move(storage_path('app/public/chat/'), $file_name);
            $chat->file = $file_name;
        }

        $chat->save();

        // Reload with relationships for the view
        $getChat = ChatModel::with(['sender', 'receiver'])->find($chat->id);

        return response()->json([
            'status'  => true,
            'success' => view('chat._single', ['value' => $getChat])->render(),
        ]);
    }

    /**
     * Poll for all messages in a conversation (AJAX, called every 5s)
     * Route: POST /admin/chat/get_messages
     */
    public function getChatMessages(Request $request)
    {
        $sender_id   = Auth::user()->id;
        $receiver_id = $request->receiver_id;

        // Mark incoming messages as read
        ChatModel::where('sender_id', $receiver_id)
            ->where('receiver_id', $sender_id)
            ->update(['status' => 1]);

        $getChat = ChatModel::with(['sender', 'receiver'])
            ->where(function ($q) use ($sender_id, $receiver_id) {
                $q->where('sender_id', $sender_id)
                  ->where('receiver_id', $receiver_id);
            })
            ->orWhere(function ($q) use ($sender_id, $receiver_id) {
                $q->where('sender_id', $receiver_id)
                  ->where('receiver_id', $sender_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status'  => true,
            'success' => view('chat._messages', ['getChat' => $getChat])->render(),
        ]);
    }
    public function markNotificationsRead(Request $request)
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
        } catch (\Exception $e) {
            // Notifications table might not exist yet
        }
        return response()->json(['status' => true]);
    }

    /**
     * Search contacts in sidebar (AJAX)
     * Route: POST /admin/chat/search_user
     */
    public function getChatSearchUser(Request $request)
    {
        $receiver_id  = $request->receiver_id;
        $sender_id    = Auth::user()->id;
        $getChatUsers = $this->getChatUsers($sender_id, $request->search);

        return response()->json([
            'status'  => true,
            'success' => view('chat._user_list', [
                'getChatUsers' => $getChatUsers,
                'receiver_id'  => $receiver_id,
            ])->render(),
        ]);
    }

    /**
     * Get unique chat contacts with their latest message
     */
    private function getChatUsers($sender_id, $search = '')
    {
        $query = ChatModel::with(['sender', 'receiver'])
            ->whereIn('id', function ($q) use ($sender_id) {
                $q->selectRaw('MAX(id)')
                  ->from('chat')
                  ->where('status', '!=', 2)
                  ->where(function ($inner) use ($sender_id) {
                      $inner->where('sender_id', $sender_id)
                            ->orWhere('receiver_id', $sender_id);
                  })
                  ->groupByRaw('IF(sender_id = ?, receiver_id, sender_id)', [$sender_id]);
            })
            ->orderBy('id', 'desc');

        if (!empty($search)) {
            $query->where(function ($q) use ($search, $sender_id) {
                $q->whereHas('sender', function ($sq) use ($search) {
                    $sq->where('name', 'like', '%' . $search . '%')
                       ->orWhere('last_name', 'like', '%' . $search . '%');
                })->orWhereHas('receiver', function ($sq) use ($search) {
                    $sq->where('name', 'like', '%' . $search . '%')
                       ->orWhere('last_name', 'like', '%' . $search . '%');
                });
            });
        }

        return $query->get();
    }
    /**
 * Search all users the current user is allowed to chat with.
 * Route: POST /student/chat/search_all_users
 */
public function searchAllUsers(Request $request)
{
    $search    = $request->search;
    $sender_id = Auth::user()->id;

    $userType = Auth::user()->user_type;

    $query = User::where('id', '!=', $sender_id)
        ->where('is_delete', 0)
        ->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('last_name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        });

    if ($userType == 3) {
        // STUDENT: Can only message Admin (1) OR Teachers (2) assigned to their class
        $myClassId = Auth::user()->class_id;
        $mySectionId = Auth::user()->section_id;

        $query->where(function($q) use ($myClassId, $mySectionId) {
            $q->where('user_type', 1) // Admins
              ->orWhere(function($subq) use ($myClassId, $mySectionId) {
                  $subq->where('user_type', 2)
                       ->whereExists(function($exists) use ($myClassId, $mySectionId) {
                           $exists->select(DB::raw(1))
                                  ->from('assign_class_teachers')
                                  ->whereColumn('assign_class_teachers.teacher_id', 'users.id')
                                  ->where('assign_class_teachers.class_id', $myClassId)
                                  ->where('assign_class_teachers.section_id', $mySectionId)
                                  ->where('assign_class_teachers.is_delete', 0)
                                  ->where('assign_class_teachers.status', 0);
                       });
              });
        });

    } elseif ($userType == 2) {
        // TEACHER: Can only message Admin (1) OR Students (3) in their assigned classes
        $query->where(function($q) use ($sender_id) {
            $q->where('user_type', 1) // Admins
              ->orWhere(function($subq) use ($sender_id) {
                  $subq->where('user_type', 3)
                       ->whereExists(function($exists) use ($sender_id) {
                           $exists->select(DB::raw(1))
                                  ->from('assign_class_teachers')
                                  ->whereColumn('assign_class_teachers.class_id', 'users.class_id')
                                  ->whereColumn('assign_class_teachers.section_id', 'users.section_id')
                                  ->where('assign_class_teachers.teacher_id', $sender_id)
                                  ->where('assign_class_teachers.is_delete', 0)
                                  ->where('assign_class_teachers.status', 0);
                       });
              });
        });
        
    } elseif ($userType == 4) {
        // PARENT: Can message Admin (1) OR Teachers (2) of their children
        $query->where(function($q) use ($sender_id) {
            $q->where('user_type', 1) // Admins
              ->orWhere(function($subq) use ($sender_id) {
                  $subq->where('user_type', 2)
                       ->whereExists(function($exists) use ($sender_id) {
                           $exists->select(DB::raw(1))
                                  ->from('assign_class_teachers')
                                  ->join('users as student', function($join) {
                                      $join->on('student.class_id', '=', 'assign_class_teachers.class_id')
                                           ->on('student.section_id', '=', 'assign_class_teachers.section_id');
                                  })
                                  ->join('student_parent', 'student_parent.student_id', '=', 'student.id')
                                  ->whereColumn('assign_class_teachers.teacher_id', 'users.id')
                                  ->where('student_parent.parent_id', $sender_id)
                                  ->where('assign_class_teachers.is_delete', 0)
                                  ->where('assign_class_teachers.status', 0);
                       });
              });
        });
    }

    $users = $query->limit(15)->get();

    $baseUrl = match(Auth::user()->user_type) {
        2 => 'teacher/chat', 3 => 'student/chat',
        4 => 'parent/chat',  5 => 'accountant/chat',
        6 => 'librarian/chat', default => 'admin/chat',
    };

    $html = '';
    foreach ($users as $user) {
        $roleName = match ($user->user_type) {
            1 => "Admin", 2 => "Teacher", 3 => "Student",
            4 => "Parent", 5 => "Accountant", 6 => "Librarian", default => "User",
        };

        $html .= '
        <a href="' . url($baseUrl) . '?receiver_id=' . $user->id . '" 
           class="d-flex align-items-center p-2 rounded-3 text-decoration-none mb-1"
           style="transition:background .15s;" 
           onmouseover="this.style.background=\'#f0f5f1\'" 
           onmouseout="this.style.background=\'\'">
            <img src="' . $user->getProfile() . '" 
                 class="rounded-circle me-3 flex-shrink-0"
                 style="width:42px;height:42px;object-fit:cover;">
            <div>
                <div class="fw-semibold text-dark" style="font-size:.88rem;">
                    ' . e($user->name . ' ' . $user->last_name) . ' <span class="badge bg-secondary ms-1" style="font-size:0.6rem;font-weight:normal;">' . $roleName . '</span>
                </div>
                <div class="text-muted" style="font-size:.76rem;">
                    ' . e($user->email) . '
                </div>
            </div>
        </a>';
    }

    if (!$users->count()) {
        $html = '<p class="text-muted text-center small py-3">No users found</p>';
    }

    return response()->json(['status' => true, 'success' => $html]);
}

    /**
     * Get global unread messages for the header dropdown (AJAX)
     */
    public function globalUnread(Request $request)
    {
        $sender_id = Auth::user()->id;
 
        // All unread messages sent TO the current user (status = 0)
        $unreadMessages = \App\Models\ChatModel::with('sender')
            ->where('receiver_id', $sender_id)
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get()
            // Group by sender so we show one row per person
            ->groupBy('sender_id');
 
        $count = \App\Models\ChatModel::where('receiver_id', $sender_id)
            ->where('status', 0)
            ->count();
 
        $baseUrl = match(Auth::user()->user_type) {
            2 => 'teacher/chat', 3 => 'student/chat',
            4 => 'parent/chat',  5 => 'accountant/chat',
            6 => 'librarian/chat', default => 'admin/chat',
        };
 
        $html = '';
 
        if ($unreadMessages->isEmpty()) {
            $html = '
            <div class="text-center py-4" style="color:#9ca3af;">
                <i class="bi bi-chat-square-text" style="font-size:2rem;opacity:.35;display:block;margin-bottom:8px;"></i>
                <div style="font-size:.78rem;">No unread messages</div>
            </div>';
        } else {
            foreach ($unreadMessages->take(5) as $senderId => $messages) {
                $sender   = $messages->first()->sender;
                $latest   = $messages->first(); // already ordered desc
                $msgCount = $messages->count();
                $preview  = \Illuminate\Support\Str::limit($latest->message ?: 'Sent an attachment', 40);
                $time     = \Carbon\Carbon::parse($latest->created_at)->diffForHumans(null, true);
                $roleName = match($sender->user_type){1=>"Admin",2=>"Teacher",3=>"Student",4=>"Parent",5=>"Accountant",6=>"Librarian",default=>"User"};
 
                $html .= '
                <a href="' . url($baseUrl) . '?receiver_id=' . $senderId . '"
                   style="display:flex;align-items:center;gap:12px;padding:10px 16px;
                          border-bottom:1px solid rgba(0,0,0,.04);text-decoration:none;
                          color:inherit;transition:background .12s;"
                   onmouseover="this.style.background=\'rgba(0,0,0,.03)\'"
                   onmouseout="this.style.background=\'\'">
                    <div style="position:relative;flex-shrink:0;">
                        <img src="' . $sender->getProfile() . '"
                             style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
                        ' . ($msgCount > 1 ? '
                        <span style="position:absolute;top:-3px;right:-3px;background:#ef4444;color:#fff;
                                     font-size:.6rem;font-weight:700;min-width:16px;height:16px;
                                     border-radius:8px;display:flex;align-items:center;justify-content:center;
                                     border:2px solid #fff;padding:0 3px;">' . $msgCount . '</span>' : '') . '
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:.82rem;font-weight:600;color:#111827;
                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            ' . e($sender->name . ' ' . $sender->last_name) . ' <span class="badge bg-secondary ms-1" style="font-size:0.6rem;font-weight:normal;">' . $roleName . '</span>
                        </div>
                        <div style="font-size:.74rem;color:#6b7280;margin-top:2px;
                                    white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            ' . e($preview) . '
                        </div>
                    </div>
                    <div style="font-size:.68rem;color:#9ca3af;flex-shrink:0;">' . $time . '</div>
                </a>';
            }
 
            // If more than 5 senders, show a "and X more" hint
            if ($unreadMessages->count() > 5) {
                $more = $unreadMessages->count() - 5;
                $html .= '
                <div style="text-align:center;padding:8px;font-size:.74rem;color:#6b7280;">
                    + ' . $more . ' more conversation' . ($more > 1 ? 's' : '') . '
                </div>';
            }
        }
 
        return response()->json([
            'count' => $count,
            'html'  => $html,
        ]);
    }
 
    /**
     * Global notifications — called by the navbar dropdown every 30s
     * Route: GET /notifications/global
     *
     * Returns: { count: int, html: string }
     *
     * This reads from the `notifications` table (Laravel's built-in).
     * Run:  php artisan notifications:table && php artisan migrate
     * If you don't use DB notifications yet, it gracefully returns empty.
     */
    public function globalNotifications(Request $request)
    {
        $user = Auth::user();
 
        // Use Laravel's built-in notification system if the table exists
        try {
            $notifications = $user->unreadNotifications()
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $count = $user->unreadNotifications()->count();
        } catch (\Exception $e) {
            // Table doesn't exist yet — return empty gracefully
            return response()->json(['count' => 0, 'html' => self::emptyNotifHtml()]);
        }
 
        if ($notifications->isEmpty()) {
            return response()->json(['count' => 0, 'html' => self::emptyNotifHtml()]);
        }
 
        $html = '';
        foreach ($notifications as $notif) {
            $data    = $notif->data;
            $icon    = $data['icon']  ?? 'bi-bell-fill';
            $bg      = $data['bg']    ?? 'rgba(59,130,246,.12)';
            $color   = $data['color'] ?? '#3b82f6';
            $title   = $data['title'] ?? 'Notification';
            $body    = $data['body']  ?? '';
            $url     = $data['url']   ?? '#';
            $time    = \Carbon\Carbon::parse($notif->created_at)->diffForHumans(null, true);
 
            $html .= '
            <a href="' . e($url) . '"
               style="display:flex;align-items:flex-start;gap:12px;padding:10px 16px;
                      border-bottom:1px solid rgba(0,0,0,.04);text-decoration:none;
                      color:inherit;transition:background .12s;background:rgba(59,130,246,.025);"
               onmouseover="this.style.background=\'rgba(0,0,0,.03)\'"
               onmouseout="this.style.background=\'rgba(59,130,246,.025)\'">
                <div style="width:36px;height:36px;border-radius:10px;background:' . e($bg) . ';
                            color:' . e($color) . ';display:flex;align-items:center;
                            justify-content:center;flex-shrink:0;font-size:.9rem;">
                    <i class="bi ' . e($icon) . '"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.8rem;font-weight:600;color:#111827;">' . e($title) . '</div>
                    ' . ($body ? '<div style="font-size:.73rem;color:#6b7280;margin-top:2px;">' . e(\Illuminate\Support\Str::limit($body, 55)) . '</div>' : '') . '
                    <div style="font-size:.68rem;color:#9ca3af;margin-top:3px;">
                        <i class="bi bi-clock me-1"></i>' . $time . '
                    </div>
                </div>
                <div style="width:7px;height:7px;border-radius:50%;background:#3b82f6;
                            flex-shrink:0;margin-top:5px;"></div>
            </a>';
        }
 
        return response()->json(['count' => $count, 'html' => $html]);
    }
 
    private static function emptyNotifHtml(): string
    {
        return '
        <div class="text-center py-4" style="color:#9ca3af;">
            <i class="bi bi-bell-slash" style="font-size:2rem;opacity:.35;display:block;margin-bottom:8px;"></i>
            <div style="font-size:.78rem;">No new notifications</div>
        </div>';
    }
}