<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sport extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function sportClasses()
    {
        return $this->hasMany(SportClass::class);
    }
}
