<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\ClassSubjectModel;
use Illuminate\Support\Facades\DB;

class ClassSubjectController extends Controller
{
    public function list()
    {
        $data['getRecord'] = ClassSubjectModel::getRecord();
        $data['header_title']= 'Assign_Subject List';
        return view('admin.assign_subject.list',$data);
    }
    public function add(Request $request){
        $data['getClass'] = ClassModel::getClass();
        $data['getSubject'] = Subject::getSubject();
        $data['header_title']= ' Add New Assign Subject';
        return view('admin.assign_subject.add',$data);
    }
       public function insert(Request $request){
        if(!empty($request->subject_id))
            {
                foreach ($request->subject_id as $subject_id) {
                    ClassSubjectModel::updateOrCreate(
                        [
                            'class_id'   => $request->class_id,
                            'subject_id' => $subject_id,
                        ],
                        [
                            'status'     => $request->status,
                            'created_by' => Auth::user()->id,
                        ]
                    );
                    

                }
                return redirect('admin/assign_subject/list')
            ->with('success', 'Subjects successfully assigned');
            }else{
                return redirect()->back()->with('error','Due to some error please try again');
            }
       
       
    }
    public function edit($id){
    $getRecord = ClassSubjectModel::find($id);

        if(!empty('getRecord'))
            {
                $data['getRecord'] = $getRecord;
                $data['getAssignSubjectID']=ClassSubjectModel::getAssignSubjectID($getRecord->class_id);
                $data['assignedSubjectIds'] = $data['getAssignSubjectID']->pluck('subject_id')->toArray();
                $data['getClass'] = ClassModel::getClass();
                $data['getSubject'] = Subject::getSubject();
                $data['header_title']= 'Edit Assign Subject';
                return view('admin.assign_subject.edit',$data);
            }else{
                abort(404);
            }
    }  
    
            public function update(Request $request)
            {
                DB::transaction(function () use ($request) {

                    // Remove old subjects for the class
                    ClassSubjectModel::where('class_id', $request->class_id)->delete();

                    // Insert new ones
                    foreach ($request->subject_id as $subject_id) {
                        ClassSubjectModel::create([
                            'class_id'   => $request->class_id,
                            'subject_id' => $subject_id,
                            'status'     => $request->status,
                            'created_by' => Auth::id(),
                        ]);
                    }
                });

                return redirect('admin/assign_subject/list')
                    ->with('success', 'Subjects successfully updated');
            }
    public function delete($id){
        $user = ClassSubjectModel::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/assign_subject/list')->with('success','Record Successfully Deleted');
    }
    public function edit_single($id){
        $getRecord = ClassSubjectModel::find($id);

        if(!empty('getRecord'))
            {
                $data['getRecord'] = $getRecord;
                $data['getClass'] = ClassModel::getClass();
                $data['getSubject'] = Subject::getSubject();
                $data['header_title']= 'Edit Assign Subject';
                return view('admin.assign_subject.edit_single',$data);
            }else{
                abort(404);
            }

    } 
    public function update_single(Request $request ,$id)
    {
      $record = ClassSubjectModel::findOrFail($id);

    $record->update([
        'class_id'   => $request->class_id,
        'subject_id' => $request->subject_id,
        'status'     => $request->status,
    ]);

    return redirect('admin/assign_subject/list')
        ->with('success', 'Subject updated successfully');
    }
}
