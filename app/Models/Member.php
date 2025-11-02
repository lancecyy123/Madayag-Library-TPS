<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id', 'name', 'email', 'phone', 
        'address', 'membership_date', 'status'
    ];

    protected $casts = [
        'membership_date' => 'date'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeBorrows()
    {
        return $this->transactions()->where('status', 'borrowed');
    }
}