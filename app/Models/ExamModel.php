<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamModel extends Model
{
    protected $table = 'exams';

    static public function getSingle($id){
        return self::find($id);
    }

        static public function getRecord()
        {
            $query = self::select('exams.*', 'users.name as created_name', 'users.last_name as created_last_name')
                ->join('users', 'users.id', '=', 'exams.created_by')
                ->where('exams.is_delete', 0);

            // Filters
            if (request('name')) {
                $query->where('exams.name', 'like', '%' . request('name') . '%');
            }

            if (request('note')) {
                $query->where('exams.note', 'like', '%' . request('note') . '%');
            }

            if (request('date')) {
                $query->whereDate('exams.created_at', '=', request('date'));
            }

            return $query->orderBy('exams.id', 'desc')->paginate(10);
        }
        static public function getExam(){
           $return = self::select('exams.*')
                ->where('exams.is_delete', 0)
                ->orderBy('exams.id', 'asc')
                ->get();

            return $return;
        
    }

}
