<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SportClassEnrollment extends Pivot
{
    use HasFactory;
    protected $table = 'sport_class_enrollments';

    protected $fillable = [
        'sport_class_id',
        'member_id',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];
}
