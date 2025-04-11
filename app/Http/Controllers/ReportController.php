<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\models\LoanPlans;
use App\models\Payments;
use DB;

class ReportController extends Controller
{
    public function ReportLoan()
    {
        $curr_date = date('Y-m');
        $plans = LoanPlans::all();
        $payees = Payments::select(
            'payments.*',
            'loan_plans.*',
            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrow"),
            DB::raw("SUM(payments.capital) as total_capital, SUM(payments.paid) as total_paid, SUM(payments.interest) as total_interest"),
        )
        ->join("borrowers", "borrowers.id", '=', 'payments.borrower_id')
        ->join("loan_plans", "loan_plans.id", '=', 'payments.plan_id')
        ->groupBy("payments.borrower_id", "payments.plan_id")
        ->whereRaw("DATE_FORMAT(payments.created_at, '%Y-%m') = ?", [$curr_date])
        ->get()
        ->groupBy("borrower_id");
        return view('admin.backend.report.reports', compact('plans', 'payees'));
    }

    public function ReportSelectDate(Request $request)
    {
        $selectedDate = $request->date_month;
        $plans = LoanPlans::all();
    
        $payees = Payments::select(
            'payments.*',
            'loan_plans.*',
            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrow"),
            DB::raw("SUM(payments.capital) as total_capital, SUM(payments.paid) as total_paid, SUM(payments.interest) as total_interest"),
        )
        ->join("borrowers", "borrowers.id", '=', 'payments.borrower_id')
        ->join("loan_plans", "loan_plans.id", '=', 'payments.plan_id')
        ->groupBy("payments.borrower_id", "payments.plan_id")
        ->whereRaw("DATE_FORMAT(payments.created_at, '%Y-%m') = ?", [$selectedDate])
        ->get()
        ->groupBy("borrower_id");
    
        return view('admin.backend.report.this_month', compact('plans', 'payees'));    
    }
    
}