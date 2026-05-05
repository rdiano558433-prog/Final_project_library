<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'author', 'isbn', 'category', 'publisher',
        'published_year', 'total_copies', 'available_copies',
        'description', 'cover_image',
    ];

    // Relationships
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    public function activeBorrowings()
    {
        return $this->hasMany(Borrowing::class)->where('status', 'borrowed');
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'like', "%$keyword%")
                     ->orWhere('author', 'like', "%$keyword%")
                     ->orWhere('isbn', 'like', "%$keyword%")
                     ->orWhere('category', 'like', "%$keyword%");
    }

    // Accessors
    public function getIsAvailableAttribute(): bool
    {
        return $this->available_copies > 0;
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->available_copies === 0) return 'Unavailable';
        if ($this->available_copies <= 2) return 'Low Stock';
        return 'Available';
    }

    // Helpers
    public function decrementCopies(): void
    {
        $this->decrement('available_copies');
    }

    public function incrementCopies(): void
    {
        if ($this->available_copies < $this->total_copies) {
            $this->increment('available_copies');
        }
    }
}