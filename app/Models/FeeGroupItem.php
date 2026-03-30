<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeGroupItem extends Model
{
    protected $table = 'fee_group_items';

    protected $fillable = [
        'fee_group_id', 'fee_type_id', 'due_date', 'amount',
    ];

    public function feeType()
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    public function group()
    {
        return $this->belongsTo(FeeGroup::class, 'fee_group_id');
    }
}