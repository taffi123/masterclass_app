<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'creativity_type_id',
        'instructor_id',
        'title',
        'description',
        'class_date',
        'start_time',
        'end_time',
        'max_participants',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'class_date' => 'date',
            'price' => 'decimal:2',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    public function creativityType(): BelongsTo
    {
        return $this->belongsTo(CreativityType::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }

    public function getScheduleLabelAttribute(): string
    {
        return sprintf(
            '%s, %s-%s',
            $this->class_date->format('d.m.Y'),
            $this->start_time->format('H:i'),
            $this->end_time->format('H:i')
        );
    }

    public function getAvailablePlacesAttribute(): int
    {
        return max(0, $this->max_participants - $this->enrollments_count);
    }

    public function getStartsAtAttribute(): Carbon
    {
        return Carbon::parse($this->class_date->format('Y-m-d').' '.$this->start_time->format('H:i:s'));
    }

    public function getEndsAtAttribute(): Carbon
    {
        return Carbon::parse($this->class_date->format('Y-m-d').' '.$this->end_time->format('H:i:s'));
    }

    public function hasStarted(): bool
    {
        return $this->starts_at->lessThanOrEqualTo(now());
    }

    public function isFull(): bool
    {
        return $this->enrollments_count >= $this->max_participants;
    }
}
