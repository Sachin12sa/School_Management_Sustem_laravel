<?php

namespace App\Http\Controllers;

use  App\Models\AssignClassTeacherModel;
use  App\Models\ClassModel;
use  App\Models\User;
use App\Models\ClassSubjectModel;
use App\Models\Subject;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AssignClassTeacherController extends Controller
{
    public function list()
    {
        $data['getRecord'] = AssignClassTeacherModel::getRecord();
        $data['header_title']= 'Assign Class Teacher List';
        return view('admin.assign_class_teacher.list',$data);
    }
    public function add(Request $request){
        $data['getTeacherClass'] = User::getTeacherClass();
        $data['getClass'] = ClassModel::getClass();
        $data['header_title']= ' Add New Assign Class Teacher ';
        return view('admin.assign_class_teacher.add',$data);
    }
       public function insert(Request $request){
        if(!empty($request->teacher_id))
            {
                foreach ($request->teacher_id as $teacher_id) {
                    AssignClassTeacherModel::updateOrCreate(
                        [
                            'class_id'   => $request->class_id,
                            'teacher_id' => $teacher_id,
                        ],
                        [
                            'status'     => $request->status,
                            'created_by' => Auth::id(),
                        ]
                    );
                    

                }
                return redirect('admin/assign_class_teacher/list')
            ->with('success', 'Teacher successfully assigned');
            }else{
                return redirect()->back()->with('error','Due to some error please try again');
            }
       
       
    }
    public function edit($class_id){
        $getRecord = AssignClassTeacherModel::find($class_id);

        if ($getRecord) {

            $data['getRecord'] = $getRecord;

            $data['AssignTeacherID'] =
                AssignClassTeacherModel::AssignTeacherID($getRecord->class_id);

            $data['assignedTeacherId'] =
                $data['AssignTeacherID']->pluck('teacher_id')->toArray();

            $data['getTeacherClass'] = User::getTeacherClass();

            $data['getClass'] = ClassModel::getClass();

            $data['header_title'] = 'Edit Assign Teacher Class';

            return view('admin.assign_class_teacher.edit', $data);
        }

        abort(404);
    }

    
            public function update(Request $request)
            {
                DB::transaction(function () use ($request) {

                    // Remove old subjects for the class
                    AssignClassTeacherModel::where('class_id', $request->class_id)->delete();

                    // Insert new ones
                    foreach ($request->teacher_id as $teacher_id) {
                        AssignClassTeacherModel::create([
                            'class_id'   => $request->class_id,
                            'teacher_id' => $teacher_id,
                            'status'     => $request->status,
                            'created_by' => Auth::id(),
                        ]);
                    }
                });

                return redirect('admin/assign_class_teacher/list')
                    ->with('success', 'Subjects successfully updated');
            }
    public function delete($id){
        $user = AssignClassTeacherModel::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/assign_class_teacher/list')->with('success','Record Successfully Deleted');
    }
    public function edit_single($id){
        $getRecord = AssignClassTeacherModel::find($id);

        if(!empty('getRecord'))
            {
                $data['getRecord'] = $getRecord;
                $data['getClass'] = ClassModel::getClass();
                $data['getTeacherClass'] = User::getTeacherClass();
                $data['header_title']= 'Edit Assign Subject';
                return view('admin.assign_class_teacher.edit_single',$data);
            }else{
                abort(404);
            }

    } 
    public function update_single(Request $request ,$class_id)
    {
      $record = AssignClassTeacherModel::findOrFail($class_id);

    $record->update([
        'class_id'   => $request->class_id,
        'teacher_id' => $request->teacher_id,
        'status'     => $request->status,
    ]);

    return redirect('admin/assign_class_teacher/list')
        ->with('success', 'Teacher updated successfully');
    }

    // teacher's section my class and subject 
    public function MyClassSubject()
    {
      $data['header_title']= 'Class and Subject List';
      $data['getRecord'] = AssignClassTeacherModel::getMyClassSubject(Auth::user()->id);
      return view('teacher.my_class_subject',$data);  
    }
}
