<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    public function major()
    {
        return $this->belongsTo(Major::class , 'major_id');
    }

    public function student()
    {
        return $this->hasMany(Student::class);
    }
    protected $fillable = [
        'name',
        'course_year',
        'major_id',
    ];
}
