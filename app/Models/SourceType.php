<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SourceType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function deals()
    {
        return $this->hasMany(Deal::class);
    }
}
