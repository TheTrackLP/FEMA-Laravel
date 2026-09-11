<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Loans;
use App\Models\LoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoansController extends Controller
{
    public function LoansDashboard(){
        return inertia('Admin/Backend/Loans', [
            'borrowers'=>Borrowers::all(),
            'types'=>LoanTypes::all(),
            'loans'=>Loans::select(
                'loans.*',
                'borrowers.fullname',
                'loan_types.name as plan',
            )
            ->join('borrowers', 'borrowers.id', '=', 'loans.borrower_id')
            ->join('loan_types', 'loan_types.id', '=', 'loans.loantype_id')
            ->get(),
        ]);
    }

    public function LoansAppliStore(Request $request){
        $valid = Validator::make($request->all(), [
            'borrower_id' => 'required',
            'loantype_id' => 'required',
            'amountborrowed' => 'required',
            'purpose' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('loans.dash')->with(
                'error', 'Error, Try Again!',
            );
        }

        $year = now()->year;

        $lastLoan = Loans::where('refno', 'like', "FEMA-LOAN-{$year}-%")
                        ->lockForUpdate()
                        ->orderByDesc('id')
                        ->first();
        $nextNumber = $lastLoan ? ((int) substr($lastLoan->refno, -5)) + 1 : 1;

        $invoice = "FEMA-LOAN-" . $year . "-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Loans::create([
            'refno' => $invoice,
            'borrower_id' => $request->borrower_id,
            'loantype_id' => $request->loantype_id,
            'amountborrowed' => $request->amountborrowed,
            'purpose' => $request->purpose,
            'currbalance' => $request->amountborrowed
        ]);

        return redirect()->route('loans.dash')->with(
            'success', 'Success, Loan Application Added',
        );
    }

    public function LoansAppliUpdate(Request $request,) {
        $currDate = date('Y-m-d');
        $valid = Validator::make($request->all(), [
            'borrower_id' => 'required',
            'loantype_id' => 'required',
            'amountborrowed' => 'required',
            'purpose' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('loans.dash')->with(
                'error', 'Error, Try Again!',
            );
        }
        
        $loanid = Loans::findorfail($request->id);

        if($request->status == 0){
            $loanid->update([
                'borrower_id' => $request->borrower_id,
                'loantype_id' => $request->loantype_id,
                'amountborrowed' => $request->amountborrowed,
                'purpose' => $request->purpose,
                'currbalance' => $request->amountborrowed,
                'status' => $request->status,
            ]);
        } elseif($request->status == 1){
            $loanid->update([
                'date_approved' => $currDate,
                'status' => $request->status,
            ]);
        }


        return redirect()->route('loans.dash')->with(
            'success', 'Success, Loan Application Update',
        );
    }
}
