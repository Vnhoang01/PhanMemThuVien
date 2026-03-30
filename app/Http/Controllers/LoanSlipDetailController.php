<?php

namespace App\Http\Controllers;

use App\Models\LoanSlipDetail;
use Illuminate\Http\Request;

class LoanSlipDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loanSlipDetails = LoanSlipDetail::with('loanSlip','books')->get();
        return view('loanSlipDetail.index', compact('loanSlipDetails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('loanSlipDetail.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_slip_id' => 'required',
            'book_id' => 'required',
            'quantity' => 'required|integer'
        ]);

        LoanSlipDetail::create($request->all());

        return redirect()->route('loanSlipDetail.index')
            ->with('success','Thêm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(LoanSlipDetail $loanSlipDetail)
    {
        return view('loanSlipDetail.show', compact('loanSlipDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanSlipDetail $loanSlipDetail)
    {
        return view('loanSlipDetail.edit', compact('loanSlipDetail'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanSlipDetail $loanSlipDetail)
    {
        $request->validate([
            'loan_slip_id' => 'required',
            'book_id' => 'required',
            'quantity' => 'required|integer'
        ]);

        $loanSlipDetail->update($request->all());

        return redirect()->route('loanSlipDetail.index')
            ->with('success','Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoanSlipDetail $loanSlipDetail)
    {
        $loanSlipDetail->delete();

        return redirect()->route('loanSlipDetail.index')
            ->with('success','Xóa thành công');
    }
}
