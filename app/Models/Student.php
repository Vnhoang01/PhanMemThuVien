<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    protected $fillable = [
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
}
