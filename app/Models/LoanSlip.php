<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanSlip extends Model
{
    public function student()
    {
        return $this->belongsTo(Student::class , 'student_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class , 'admin_id');
    }

    public function details()
    {
        return $this->hasMany(LoanSlipDetail::class , 'loan_slip_id');
    }

    protected $fillable = [
        'admin_id',
        'student_id',
        'start_date',
        'due_date',
        'return_date',
        'total_quantity',
        'total_fine',
        'status',
    ];
}
