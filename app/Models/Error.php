<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    protected $fillable = [
        'name',
        'fine_amount',
    ];

    // 🔗 n-n ngược lại
    public function loanSlipDetails()
    {
        return $this->belongsToMany(
            LoanSlipDetail::class,
            'loan_slip_detail_error'
        )->withPivot('fine_amount')
            ->withTimestamps();
    }
}
