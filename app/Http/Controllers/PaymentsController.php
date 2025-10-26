<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use App\Models\Borrower;
use App\Models\LoanLists;
use App\Models\LoanPlans;
use App\Models\LoanSchedules;
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
            ->orderBy('created_at','desc')
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
        $plans = LoanPlans::all();
        return view('admin.backend.payments', compact('loans', 'payees', 'plans'));
    }

    public function GetLoanData($id)
    {
        $loanD = DB::table('loan_lists')
                    ->select(
                        "loan_lists.*",
                        "loan_plans.interest_percentage",
                        "loan_plans.penalty_rate",
                        "loan_schedules.date_due"
                        )
                    ->join('loan_plans', 'loan_plans.id', '=', 'loan_lists.plan_id')
                    ->join('loan_schedules', 'loan_schedules.loan_id', '=', 'loan_lists.id')
                    ->where([
                        ["loan_lists.id", "=", $id],
                        ["loan_schedules.status", "=", 0],
                        ])
                    ->orderBy('loan_schedules.date_due')
                    ->first();

        return response()->json([
            'status'=>200,
            'loanD'=>$loanD,
        ]);
    } 

    public function AddPayment(Request $request)
    {
        $curr_date = date('Y-m-d H:i:s');
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

        LoanSchedules::where('loan_id', $request->loan_id)
            ->where('status', 0)
            ->orderBy('date_due')
            ->limit(1)
            ->update(['status' => 1]);

        $curr_balance = LoanLists::select("amount")->where('id', $request->loan_id)->sum('amount');
        $curr_capital = Borrower::select("shared_capital")->where('id', $request->borrower_id)->sum('shared_capital');

        $minus_balance = $curr_balance - $request->input('paid');
        $plus_capital = $curr_capital + $request->input('capital');

        LoanLists::where('id', $request->loan_id)
                    ->update([
                        'amount' => $minus_balance
                    ]);

        Borrower::where('id', $request->borrower_id)
                    ->update([
                        'shared_capital' => $plus_capital
                    ]);

        return redirect()->route('pay.list')
                    ->with([
                       'message' => 'New Payment Added Successfully',
                       'alert-type' => 'success',
        ]);
    }
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