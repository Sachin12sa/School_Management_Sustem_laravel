<?php

namespace App\Models;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    static public function getNameSingle($name){
        return ClassModel::where('name','=',$name)->first();
    }

    static public function getRecord(){
            $return = ClassModel::select('classes.*', 'users.name as created_by_name')
                ->join('users', 'users.id', 'classes.created_by');

            if (request('name')) {
                $return->where('classes.name', 'like', '%' . request('name') . '%');
            }

            if (request('date')) {
                $return->whereDate('classes.created_at', request('date'));
            }

            $return = $return->where('classes.is_delete', 0)
                ->orderBy('classes.id', 'desc')
                ->paginate(10);

            return $return;
    }
    static public function getSingle($id){
        return ClassModel::find($id);
    }
    static public function getClass(){
           $return = ClassModel::select('classes.*')
                ->join('users', 'users.id', 'classes.created_by')
                ->where('classes.is_delete', 0)
                ->where('classes.status', 0)
                ->orderBy('classes.id', 'asc')
                ->get();

            return $return;
        
    }
}

