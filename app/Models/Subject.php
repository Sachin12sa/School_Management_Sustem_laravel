<?php

namespace App\Models;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    static public function getRecord(){
        $return = Subject::select('subjects.*','users.name as created_by_name')
        ->join('users','users.id','=','subjects.created_by');
        if (request('name')) {
                $return->where('subjects.name', 'like', '%' . request('name') . '%');
            }
            if (request('type')) {
                $return->where('subjects.type', 'like', '%' . request('type') . '%');
            }
        if(request('date')){
            $return->whereDate('subjects.created_at',request('date'));
        }
        $return= $return->where('subjects.is_delete','=',0)
        ->orderBy('subjects.id','desc')
        ->paginate(10);
        return $return;
    }
    static public function getSingle($id){
        return Subject::find($id);
    }
    static public function  getSubject(){
        $return = Subject::select('subjects.*')
        ->join('users','users.id','=','subjects.created_by')
        ->where('subjects.is_delete','=',0)
        ->where('subjects.status','=',0)
        ->orderBy('subjects.name','asc')
        ->get();
        return $return;
    }

}
