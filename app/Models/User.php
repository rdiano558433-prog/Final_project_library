<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'student_id', 'role', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class, 'user_id');
    }

    public function issuedBorrowings()
    {
        return $this->hasMany(Borrowing::class, 'issued_by');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isAdminOrStaff(): bool
    {
        return in_array($this->role, ['admin', 'staff']);
    }

    // Scopes
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Accessors
    public function getActiveBorrowingsCountAttribute(): int
    {
        return $this->borrowings()->where('status', 'borrowed')->count();
    }

    public function getOverdueBorrowingsAttribute()
    {
        return $this->borrowings()->where('status', 'overdue')->get();
    }
}