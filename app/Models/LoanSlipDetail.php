<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSlipDetail extends Model
{
    protected $fillable = [
        'loan_slip_id',
        'book_detail_id',
        'fine_amount',
        'status',
    ];
    public function loanSlip()
    {
        return $this->belongsTo(LoanSlip::class);
    }

    public function bookDetail()
    {
        return $this->belongsTo(BookDetail::class);
    }

    public function errors()
    {
        return $this->belongsToMany(
            Error::class,
            'loan_slip_detail_error'
        )->withPivot('fine_amount')
            ->withTimestamps();
    }
}
