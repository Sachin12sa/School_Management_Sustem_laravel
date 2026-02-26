<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarksGradeModel extends Model
{
    protected $table = 'marks_grades';

    static public function getSingle($id){
        return self::find($id);
    }

    static public function getRecord()
    {
        return self::select('marks_grades.*','users.name as created_name','users.last_name as created_last_name')
        ->join('users','users.id','=','marks_grades.created_by')
        ->get();
    }

    static public function getGrade($percent)
        {
            $return = self::select('marks_grades.*')
                ->where('percent_from', '<=', $percent)
                ->where('percent_to', '>=', $percent)
                ->first();

            return !empty($return->name) ? $return->name : '';
        }
}

