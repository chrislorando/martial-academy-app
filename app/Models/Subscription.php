<?php

namespace App\Models;

use App\Enums\SubscriptionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'subscription_type',
        'subscription_value',
        'start_date',
        'end_date',
        'sessions_remaining',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'sessions_remaining' => 'integer',
        'subscription_type' => SubscriptionType::class
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'Active';
    }

    public function isExpired(): bool
    {
        return $this->status === 'Expired';
    }

    public function isFrozen(): bool
    {
        return $this->status === 'Frozen';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'Completed';
    }

    public function isSessionBased(): bool
    {
        return $this->subscription_type === 'Session Based';
    }

    public function isMonthly(): bool
    {
        return $this->subscription_type === 'Monthly';
    }
}
