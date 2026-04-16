<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\SportClassFactory;

class SportClass extends Model
{
    use HasFactory;

    protected static function newFactory(): SportClassFactory
    {
        return SportClassFactory::new();
    }

    protected $table = 'sport_classes';

    protected $fillable = [
        'name',
        'sport_id',
        'coach_id',
        'day_of_week',
        'start_time',
        'end_time',
        'max_capacity',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'sport_class_enrollments', 'sport_class_id', 'member_id')
            ->using(SportClassEnrollment::class)
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getEnrolledCountAttribute(): int
    {
        return $this->members()->count();
    }

    public function getAvailableSlotsAttribute(): int
    {
        return max(0, $this->max_capacity - $this->enrolled_count);
    }

    public function isFull(): bool
    {
        return $this->enrolled_count >= $this->max_capacity;
    }
}
