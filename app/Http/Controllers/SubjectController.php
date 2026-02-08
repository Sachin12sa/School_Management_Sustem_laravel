<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Auth;
class SubjectController extends Controller
{
    public function list(){
        
        $data['getRecord'] = Subject::getRecord();
        $data['header_title']= 'Subject List';
        return view('admin.subject.list',$data);
    }
    public function add(){
        $data['header_title']= ' Add New Subject';
        return view('admin.subject.add',$data);
    }
    public function insert(Request $request){
        // dd($request)->all();
        $save = new Subject;
        $save->name = trim($request->name);
        $save->type = trim($request->type);
        $save->status = trim($request->status);
        $save->created_by = Auth::user()->id;
        $save->save();
        return redirect('admin/subject/list')->with('success','Subject Successfully Created');
    }
      public function edit($id){
        $data['getRecord'] = Subject::getSingle($id);
        if(!empty($data['getRecord']))
            {
                $data['header_title']= 'Edit  Subject';
        return view('admin.subject.edit',$data);
            }else{
                abort(404);
            }


    }  
     public function update($id, Request $request){
        $user = Subject::getSingle($id);
        $user->name = trim($request -> name);
        $user  ->type = $request->type;
        $user  ->status = $request->status;   
        $user->save();
            return redirect('admin/subject/list')->with('success','Subject Successfully updated');
        
    }
    public function delete($id){
        $user = Subject::getSingle($id);
        $user->is_delete = 1;
        $user->save();
        return redirect('admin/subject/list')->with('success','Class Successfully Deleted');
    } 
}
