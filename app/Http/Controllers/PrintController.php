<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use DB;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function PrintReceipt($id){
        $payee = Payments::select(
            'payments.*',
            'loan_lists.loan_refno',
            'loan_plans.plan_name',
            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrower"),
            DB::raw("CONCAT(loan_plans.plan_name) as plan"),
            )
            ->join('borrowers', 'borrowers.id', '=', 'payments.borrower_id')
            ->join('loan_plans', 'loan_plans.id', '=', 'payments.plan_id')
            ->join('loan_lists', 'loan_lists.id', '=', 'payments.loan_id')
            ->findOrFail($id);
        return view('admin.backend.print.receipts', compact('payee'));
    }
}