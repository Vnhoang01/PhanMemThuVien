<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_number',
    ];

    public function book()
    {
        return $this->hasMany(Book::class);
    }
}
