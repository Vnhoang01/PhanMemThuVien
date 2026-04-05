<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookDetail extends Model
{
    protected $fillable = [
        'barcode',
        'name',
        'status',
        'book_id'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function loanSlipDetails()
    {
        return $this->hasMany(LoanSlipDetail::class);
    }
}
