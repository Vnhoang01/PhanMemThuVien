<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    protected $fillable = [
        'student_code',
        'name',
        'date_of_birth',
        'gender',
        'class_id',
        'email',
        'password',
        'phone_number',
        'address',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}
