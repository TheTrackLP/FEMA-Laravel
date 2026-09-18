<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Loans;
use App\Models\LoanSchedules;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends Controller
{
    public function PaymentsDashboard(){
        return inertia('Admin/Backend/Payments', [
            'payments'=>Payments::select(
                'payments.*',
                'borrowers.fullname',
                'loan_types.name as plan',
                'loans.refno',
                'loans.currbalance',
            )
            ->join('loans','loans.id','=','payments.loan_id')
            ->join('borrowers','borrowers.id','=','payments.borrower_id')
            ->join('loan_types','loan_types.id','=','payments.type_id')
            ->get(),
            
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

    public function PaymentsStore(Request $request){
        $valid = Validator::make($request->all(), [
            'loan_id' => 'required',
            'borrower_id' => 'required',
            'principal' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('pays.dash')->with(
                'error', 'Error, Try Again',
            );
        }
        $year = now()->year;

        $lastLoan = Payments::where('ofrec', 'like', "FEMA-PAY-{$year}-%")
                        ->lockForUpdate()
                        ->orderByDesc('id')
                        ->first();
        $nextNumber = $lastLoan ? ((int) substr($lastLoan->refno, -5)) + 1 : 1;

        $invoice = "FEMA-PAY-" . $year . "-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $updateLoanApp = Loans::findorfail($request->loan_id);

        $balanceAfter = $updateLoanApp->currbalance - $request->principal;
        $totalPaid = $request->principal + $request->interest + $request->penalty;

        $updateLoanApp->update([
            'currbalance' => $balanceAfter,
        ]);

        Payments::create([
            'loan_id' => $request->loan_id,
            'borrower_id' => $request->borrower_id,
            'ofrec' => $invoice,
            'type_id' => $request->type_id,
            'total_paid' => $totalPaid,
            'balance_after' => $balanceAfter,
            'principal' => $request->principal,
            'interest' => $request->interest,
            'capital' => $request->capital,
            'penalty' => $request->penalty,
        ]);

       return redirect()->route('pays.dash')->with(
            'success', 'Success, Payment Successfull',
        );
    }

    public function currentSchedule($id){
        $offset = Payments::where('loan_id', $id)->count();
        $schedule = LoanSchedules::where('loan_id', $id)
                ->limit(1)
                ->offset($offset)
                ->get();

        return response()->json([
            'schedule'=>$schedule,
            'offset'=>$offset,
        ]);
    }
}
