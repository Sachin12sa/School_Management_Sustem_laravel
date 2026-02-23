<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekModel extends Model
{
    protected $table = 'weeks';
    protected $fillable = ['name'];

    static public function getRecord()
    {
        return self::get();
    }
     static public function getWeekUsingName($weekname)
    {
        return self::where('name','=',$weekname)->first();
        
    }
}
