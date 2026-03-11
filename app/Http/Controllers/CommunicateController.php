<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailUserMail;
use App\Models\NoticeBoardMessageModel;
use App\Models\NoticeBoardModel;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommunicateController extends Controller
{
//     
    public function noticeBoard()
    {

        $data['header_title'] = 'Notice Board';
        $data['getRecord'] = NoticeBoardModel::getRecord();
        return view('admin.communicate.noticeboard.list', $data);
    }
    
    public function addNoticeBoard(){
        $data['header_title']= 'Add New Notice Board';
        return view('admin.communicate.noticeboard.add',$data);
    }
    
    public function insertNoticeBoard(Request $request){
        $save = new NoticeBoardModel;
        $save->title = $request->title;
        $save->notice_date = $request->notice_date;
        $save->publish_date = $request->publish_date;
        $save->message = $request->message;
        $save->created_by = Auth::user()->id;
        $save->save();
        
        if(!empty($request->message_to))
            {
                foreach($request->message_to as $message_to)
            {
                $message = new NoticeBoardMessageModel;
                $message->notice_board_id = $save->id;
                $message->message_to = $message_to;
                $message->save();

            }
            }
        
        return redirect('admin/communicate/notice_board')->with('success','Notice Board Successfully Created.');
    }
    public function editNoticeBoard($id){
        
        $data['getRecord'] = NoticeBoardModel::with('getMessage')->find($id);    
        if(!empty($data['getRecord']))
            {
                $data['header_title']= 'EditNotice Board';
        return view('admin.communicate.noticeboard.edit',$data);
            }else{
                abort(404);
            }
        
        
    }
    public function updateNoticeBoard($id, Request $request)
        {
            $save = NoticeBoardModel::find($id);

            if (!$save) {
                abort(404);
            }

            $save->title = trim($request->title);
            $save->notice_date = trim($request->notice_date);
            $save->publish_date = trim($request->publish_date);
            $save->message = trim($request->message);
            $save->save();

            // 🔥 Delete old message_to first
            NoticeBoardMessageModel::where('notice_board_id', $save->id)->delete();

            if (!empty($request->message_to)) {
                foreach ($request->message_to as $message_to) {
                    NoticeBoardMessageModel::create([
                        'notice_board_id' => $save->id,
                        'message_to' => $message_to
                    ]);
                }
            }

            return redirect('admin/communicate/notice_board')
                    ->with('success', 'Notice Board Successfully Updated.');
        }
    public function deleteNoticeBoard($id){
        $user = NoticeBoardModel::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/communicate/notice_board')->with('success','Notice Board Successfully Deleted');
    } 

    // My Student side 
    public function myNoticeBoardStudent()
    {
        $student_id = Auth::user()->user_type;
        $data['header_title'] = 'My Notice Board';
        $data['getRecord'] = NoticeBoardModel::getRecordUser($student_id);
        return view('student.my_notice_board', $data);
    }
    // teacher side
    public function myNoticeBoardTeacher()
    {
        $student_id = Auth::user()->user_type;
        $data['header_title'] = 'My Notice Board';
        $data['getRecord'] = NoticeBoardModel::getRecordUser($student_id);
        return view('student.my_notice_board', $data);
    }

    // parent side 
    public function myNoticeBoardParent()
    {
        $student_id = Auth::user()->user_type;
        $data['header_title'] = 'My Notice Board';
        $data['getRecord'] = NoticeBoardModel::getRecordUser($student_id);
        return view('student.my_notice_board', $data);
    }
    // Send Emails
    public function sendEmail()
    {

        $data['header_title'] = 'Send Mail';
        $data['getRecord'] = NoticeBoardModel::getRecord();
        return view('admin.communicate.send_email', $data);
    }

    public function searchEmail(Request $request)
        {
            $json = [];

            if (!empty($request->search)) {

                $getUser = User::SearchUser($request->search);

                foreach ($getUser as $value) {
                    $type = '';
                    if($value->user_type == 1)
                        {
                            $type = 'Admin';
                        }
                    elseif($value->user_type == 2)
                        {
                            $type = 'Teacher';
                        }
                    elseif($value->user_type == 3)
                        {
                            $type = 'Student';
                        } 
                    elseif($value->user_type == 4)
                        {
                            $type = 'parent';
                        }        

                    $name = $value->name . ' ' . $value->last_name.'-'.$type;

                    $json[] = [
                        'id' => $value->id,
                        'text' => $name
                    ];
                }
            }

            return response()->json($json);
        }
public function sendEmailUser(Request $request)
    {
        if (!empty($request->user_id)) {

            foreach ($request->user_id as $user_id) {

                $user = User::getSingle($user_id);

                if (!empty($user)) {

                    $user->send_message = $request->message;
                    $user->send_subject = $request->subject;

                    Mail::to($user->email)->send(new SendEmailUserMail($user));

                    sleep(2); // prevent Mailtrap rate limit
                }
            }
        }

        // Send email based on user type
        if (!empty($request->message_to)) {

            foreach ($request->message_to as $user_type) {

                $getUser = User::getUser($user_type);

                foreach ($getUser as $value) {

                    $value->send_message = $request->message;
                    $value->send_subject = $request->subject;

                    Mail::to($value->email)->send(new SendEmailUserMail($value));

                    sleep(2); // prevent Mailtrap rate limit
                }
            }
        }
    return redirect()->back()->with('success', 'Email Successfully Sent.');
    }
}
