<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'avatar',
        'about',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function masterClasses(): HasMany
    {
        return $this->hasMany(MasterClass::class, 'instructor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function bookedClasses(): BelongsToMany
    {
        return $this->belongsToMany(MasterClass::class, 'enrollments');
    }

    public function isInstructor(): bool
    {
        return $this->role === 'instructor';
    }

    public function isVisitor(): bool
    {
        return $this->role === 'visitor';
    }
}
