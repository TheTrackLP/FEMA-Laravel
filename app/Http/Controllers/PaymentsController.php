<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Borrower;
use App\Models\LoanLists;
use App\Models\LoanPlans;
use App\Models\Payments;

class PaymentsController extends Controller
{
    public function PaymentLists()
    {
        $payees = Payments::select(
            'payments.*',
            'loan_lists.loan_refno',
            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrow"),
            DB::raw("CONCAT(loan_plans.plan_name) as plan"),
            )
            ->join('borrowers', 'borrowers.id', '=', 'payments.borrower_id')
            ->join('loan_plans', 'loan_plans.id', '=', 'payments.plan_id')
            ->join('loan_lists', 'loan_lists.id', '=', 'payments.loan_id')
            ->get();

        $loans = LoanLists::select(
            'loan_lists.*',
            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.lastname) as borrow"),
            DB::raw("CONCAT(loan_plans.plan_name) as plan"),
            )
            ->join('borrowers', 'borrowers.id', '=', 'loan_lists.borrower_id')
            ->join('loan_plans', 'loan_plans.id', '=', 'loan_lists.plan_id')
            ->where('loan_lists.status', 2)
            ->get();
        return view('admin.backend.payment.payments', compact('loans', 'payees'));
    }

    public function GetLoanData($id)
    {
        $loanD = LoanLists::findorfail($id);
        return response()->json([
            'status'=>200,
            'loanD'=>$loanD,
        ]);
    } 

    public function AddPayment(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'off_rec' => 'required',
            'loan_id' => 'required',
            'plan_id' => 'required',
            'borrower_id' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('pay.list')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again',
                                'alert-type' => 'error',
                             ]);
        }

        Payments::create([
            'off_rec'  => $request->off_rec,
            'loan_id'  => $request->loan_id,
            'plan_id'  => $request->plan_id,
            'borrower_id'  => $request->borrower_id,
            'paid'  => $request->paid,
            'interest'  => $request->interest,
            'capital'  => $request->capital,
            'penalty'  => $request->penalty,
        ]);

        return redirect()->route('pay.list')
                    ->with([
                       'message' => 'New Payment Added Successfully',
                       'alert-type' => 'success',
        ]);
    }
}