<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    static public function  getRecord(){
        $return = Subject::select('subjects.*','users.name as created_by_name')
        ->join('users','users.id','=','subjects.created_by');
        if(request('name')){
            $return->where('subjects.name','like','%'.request('name','%'));
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
}
