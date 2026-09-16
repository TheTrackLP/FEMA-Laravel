<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Loans;
use App\Models\LoanSchedules;
use App\Models\LoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
                'borrowers.sharedcapital',
                'borrowers.yearservice',
                'borrowers.datejoined',
                'loan_types.name as plan',
                DB::raw("CONCAT(loan_types.name, ' [',loan_types.interest_rate , '% interest, ', loan_types.penalty,'% penalty]') as fullplan"),
            )
            ->join('borrowers', 'borrowers.id', '=', 'loans.borrower_id')
            ->join('loan_types', 'loan_types.id', '=', 'loans.loantype_id')
            ->get(),
        ]);
    }

    public function LoansAppliStore(Request $request){
        $currDate = date('Y-m-d H:i:s');
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
            'currbalance' => $request->amountborrowed,
            'date_applied' => $currDate,
        ]);

        return redirect()->route('loans.dash')->with(
            'success', 'Success, Loan Application Added',
        );
    }

    public function LoansAppliUpdate(Request $request) {
        $currDate = date('Y-m-d H:i:s');
        $min = 500;
        $days = 15;
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
        if($request->status == 1){
            $loanid->update([
                'date_approved' => $currDate,
                'status' => $request->status,
            ]);
        }
        if($request->status == 2){
            $loanid->update([
                'date_released' => $currDate,
            ]);
            $roundoff = $request->amountborrowed / $min;
            for ($i=1; $i < $roundoff; $i++) { 
                $date = date('Y-m-d', strtotime(date('Y-m-d'). "+". $i*$days."days"));
                $check = LoanSchedules::where([
                    ['loan_id', '=', $loanid->id],
                    ['date_due', '=', $date]
                ])->count();

                if ($check > 0) {
                    LoanSchedules::findorfail($loanid->id)->update([
                        'loan_id' => $loanid->id,
                        'date_due' => $date,
                        'status' => 0,
                    ]);
                } else {
                    LoanSchedules::create([
                        'loan_id' => $loanid->id,
                        'date_due' => $date,
                        'status' => 0,
                    ]);
                }
            }
        }

        $loanid->update([
            'borrower_id' => $request->borrower_id,
            'loantype_id' => $request->loantype_id,
            'amountborrowed' => $request->amountborrowed,
            'purpose' => $request->purpose,
            'currbalance' => $request->amountborrowed,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);


        return redirect()->route('loans.dash')->with(
            'success', 'Success, Loan Application Update',
        );
    }
}
