<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Loans;
use App\Models\LoanSchedules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    public function PaymentsDashboard(){
        return inertia('Admin/Backend/Payments', [
            'loans'=>Loans::select(
                'loans.*',
                'borrowers.fullname',
                'borrowers.sharedcapital',
                'loan_types.interest_rate',
                'loan_types.penalty',
                DB::raw("CONCAT(borrowers.fullname, ' | ', loan_types.name, ' [',loan_types.interest_rate , '% interest, ', loan_types.penalty,'% penalty]') as fulldetails"),
            )
            ->join('borrowers', 'borrowers.id', '=', 'loans.borrower_id')
            ->join('loan_types', 'loan_types.id', '=', 'loans.loantype_id')
            ->get(),
            'loanTimeline'=>LoanSchedules::all(),
        ]);
    }
}
