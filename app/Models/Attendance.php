<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'sport_class_id',
        'member_id',
        'subscription_id',
        'date',
        'attendance_time',
        'status',
        'marked_by',
    ];

    protected $casts = [
        'date' => 'datetime',
        'attendance_time' => 'datetime:H:i:s',
    ];

    public function sportClass()
    {
        return $this->belongsTo(SportClass::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    public function isPresent(): bool
    {
        return $this->status === 'Present';
    }

    public function isAbsent(): bool
    {
        return $this->status === 'Absent';
    }

    public function isLate(): bool
    {
        return $this->status === 'Late';
    }

    public function isMarked(): bool
    {
        return $this->status !== null;
    }

    public function isNotMarked(): bool
    {
        return $this->status === null;
    }

    public function getMarkedStatusAttribute(): ?string
    {
        return $this->status;
    }
}
