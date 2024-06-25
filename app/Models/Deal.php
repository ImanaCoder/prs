<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Deal extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'name', 'client_id', 'work_type',
        'source_type_id', 'deal_value', 'deal_date',
        'due_date', 'remarks', 'deal_version','status','user_id'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sourceType()
    {
        return $this->belongsTo(SourceType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
