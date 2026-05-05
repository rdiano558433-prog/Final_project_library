<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'issued_by',
        'returned_to',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
        'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Unknown User'
        ]);
    }

    public function book()
    {
        return $this->belongsTo(Book::class)->withDefault([
            'title' => 'Deleted Book'
        ]);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by')->withDefault([
            'name' => 'System'
        ]);
    }

    public function returnedTo()
    {
        return $this->belongsTo(User::class, 'returned_to')->withDefault([
            'name' => 'System'
        ]);
    }

   
    public function scopeBorrowed($query)
    {
        return $query->where('status', 'borrowed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'borrowed')
                  ->where('due_date', '<', now());
            });
    }

    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

  
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->due_date) return false;

        return $this->status === 'borrowed' && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) return 0;

        return $this->due_date->diffInDays(now());
    }

    public function getCalculatedFineAttribute(): float
    {
        return $this->days_overdue * 5.00;
    }

  

    public static function markOverdueRecords(): void
    {
        self::where('status', 'borrowed')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }
}