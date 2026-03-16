<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $table = 'fee_types';

    protected $fillable = [
        'name', 'amount', 'frequency', 'description', 'status', 'created_by',
    ];

    public static function getRecord()
    {
        return self::where('is_delete', 0)->orderBy('name')->get();
    }

    public static function getActive()
    {
        return self::where('is_delete', 0)->where('status', 1)->orderBy('name')->get();
    }

    public static function getSingle($id)
    {
        return self::find($id);
    }

    public function fees()
    {
        return $this->hasMany(StudentFee::class, 'fee_type_id');
    }

    public function getFrequencyLabelAttribute(): string
    {
        return match($this->frequency) {
            'monthly'     => 'Monthly',
            'quarterly'   => 'Quarterly',
            'yearly'      => 'Yearly',
            'one_time'    => 'One Time',
            default       => ucfirst($this->frequency),
        };
    }
}