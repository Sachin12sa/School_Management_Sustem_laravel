<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeBoardModel extends Model
{
    protected $table = "notice_boards";
    protected $fillable = [
    'title',
    'notice_date',
    'publish_date',
    'message',
    'created_by'
];

    static public function getSingle($id){
        return self::find($id);
    }

    static public function getRecord()
    {
        $return = self::select('notice_boards.*','users.name as created_by_name')
                    ->join('users','users.id','=','notice_boards.created_by')
                    ->orderBy('notice_boards.id','asc');
                    if (request('title')) {
                        $return->where('notice_boards.title', 'like', '%' . request('title') . '%');
                            }
                    if (!empty(request('notice_date'))) {
                        $return->whereDate('notice_boards.notice_date', request('notice_date'));
                    }
                    if (!empty(request('publish_date'))) {
                        $return->whereDate('notice_boards.publish_date', request('publish_date'));
                    }
                    if (request('message')) {
                        $return->where('notice_boards.message', 'like', '%' . request('message') . '%');
                            }    

                $return= $return ->where('notice_boards.is_delete', 0)
                    ->paginate(10);
            return $return;
    }
 
     public function getMessage()
        {
            return $this->hasMany(NoticeBoardMessageModel::class, 'notice_board_id');
        }

    static public function getRecordUser($message_to)
        {
            $return = self::select('notice_boards.*','users.name as created_by_name')
                ->join('users','users.id','=','notice_boards.created_by')
                ->join('notice_board_messages','notice_board_messages.notice_board_id','=','notice_boards.id');
                 if (request('title')) {
                        $return->where('notice_boards.title', 'like', '%' . request('title') . '%');
                            }
                    
                    if (!empty(request('publish_date'))) {
                        $return->whereDate('notice_boards.publish_date', request('publish_date'));
                    }
                    if (request('message')) {
                        $return->where('notice_boards.message', 'like', '%' . request('message') . '%');
                            }    

               $return= $return ->where('notice_board_messages.message_to','=',$message_to)
                ->where('notice_boards.publish_date','<=',date('Y-m-d'))
                ->where('notice_boards.is_delete', 0)
                ->orderBy('notice_boards.id','asc')
                ->distinct() // prevents duplicate rows
                ->paginate(10);

            return $return;
        }


}

