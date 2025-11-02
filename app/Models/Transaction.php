<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 'member_id', 'borrow_date', 
        'due_date', 'return_date', 'status', 'fine_amount'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function isOverdue()
    {
        return $this->status === 'borrowed' && Carbon::now()->gt($this->due_date);
    }

    public function calculateFine()
    {
        if ($this->status === 'returned' || !$this->isOverdue()) {
            return 0;
        }

        $daysOverdue = Carbon::now()->diffInDays($this->due_date);
        return $daysOverdue * 5; // 5 pesos per day
    }
}