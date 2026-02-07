<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function list(){
        
        // $data['getRecord'] = ClassModel::getRecord();
        $data['header_title']= 'Subject List';
        // dd(ClassModel::getClass());
        return view('admin.subject.list',$data);
    }
}
