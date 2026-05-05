<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'book_id', 'issued_by', 'returned_to',
        'borrow_date', 'due_date', 'return_date',
        'status', 'fine_amount', 'notes',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date'    => 'date',
        'return_date' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function returnedTo()
    {
        return $this->belongsTo(User::class, 'returned_to');
    }

    // Scopes
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

    // Accessors
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'borrowed' && $this->due_date->isPast();
    }

    public function getDaysOverdueAttribute(): int
    {
        if (!$this->is_overdue) return 0;
        return $this->due_date->diffInDays(now());
    }

    public function getCalculatedFineAttribute(): float
    {
        // ₱5 per day overdue
        return $this->days_overdue * 5.00;
    }

    // Static helpers
    public static function markOverdueRecords(): void
    {
        self::where('status', 'borrowed')
            ->where('due_date', '<', now()->toDateString())
            ->update(['status' => 'overdue']);
    }
}