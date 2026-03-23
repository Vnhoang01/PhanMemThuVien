<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSlipDetail extends Model
{
    protected $fillable = [
        'loan_slip_id',
        'book_id',
        'fine_amount',
        'status',
    ];
    public function loanslip()
    {
        return $this->belongsTo(LoanSlip::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
