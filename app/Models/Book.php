<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function author()
    {
        return $this->belongsTo(Author::class , 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class , 'publisher_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class , 'category_id');
    }

    protected $fillable = [
        'name',
        'author_id',
        'category_id',
        'publisher_id',
        'year_of_publication',
        'total_quantity',
        'available_quantity',
        'status',
        'description',
    ];
}
