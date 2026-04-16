<?php

namespace App\Models;

use App\Enums\MembershipLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'date_of_birth',
        'sport_id',
        'level',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'level' => MembershipLevel::class
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'Active');
    }

    public function sportClasses()
    {
        return $this->belongsToMany(SportClass::class, 'sport_class_enrollments', 'member_id', 'sport_class_id')
            ->using(SportClassEnrollment::class)
            ->withPivot('enrolled_at')
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
