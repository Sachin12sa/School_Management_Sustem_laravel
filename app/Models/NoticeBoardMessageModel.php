<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeBoardMessageModel extends Model
{
    protected $table = "notice_board_messages";
     protected $fillable = [
    'title',
    'notice_board_id',
    'message_to',
 
];

    static public function getSingle($id){
        return self::find($id);
    }
    
}
