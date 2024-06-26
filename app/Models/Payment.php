<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Payment extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'deal_id', 'receipt_path',
        'payment_date', 'payment_value',
        'remarks', 'payment_version','status','verified_by_id','verified_at','verification_remarks'
    ];

    // Relationships
    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function verified_by()
    {
        return $this->belongsTo(User::class,'verified_by_id');
    }
}
