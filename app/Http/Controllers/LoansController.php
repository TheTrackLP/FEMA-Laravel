<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use DB;
use Validator;
use App\Models\LoanPlans;
use Carbon\carbon;
use App\Models\LoanLists;
use App\Models\LoanSchedules;

class LoansController extends Controller
{
    public function LoansLists()
    {
        $loans = DB::table('loan_lists')
                        ->select(
                            'loan_lists.*',
                            'borrowers.borrower_ref',
                            'loan_plans.interest_percentage',
                            'loan_plans.penalty_rate',
                            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.lastname) as borrow"),
                            DB::raw("CONCAT(loan_plans.plan_name) as plan"),
                        )
                        ->join('borrowers', 'loan_lists.borrower_id', '=', 'borrowers.id')
                        ->join('loan_plans', 'loan_lists.plan_id', '=', 'loan_plans.id')
                        ->get();
        $borrowers = DB::table('borrowers')
                            ->select(
                                'borrowers.*',
                                DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.lastname) as borrower"),
                            )
                            ->where('borrowers.status', 1)
                            ->get();
        $plans = LoanPlans::all();
        return view('admin.backend.loans', compact('borrowers', 'plans', 'loans'));
    }

    public function GetBorrower($id)
    {
        $borrow = Borrower::findorfail($id);
        return response()->json([
            'status'=>200,
            'borrow'=>$borrow,
        ]);
    }

    public function AddApplication(Request $request)
    {
        $ref_no = rand(0000000, 9999999);
        $valid = Validator::make($request->all(),[
            'borrower_id' => 'required',
            'shared_capital' => 'required',
            'plan_id' => 'required',
            'amount' => 'required',
            'purpose' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('loans.dash')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error'
                             ]);
        }

        LoanLists::create([
            'loan_refno' => $ref_no,
            'borrower_id' => $request->borrower_id,
            'shared_capital' => $request->shared_capital,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'amount_borrow' => $request->amount,
            'purpose' => $request->purpose,
            'date_released' => null,
            'status' => 0,
        ]);

        return redirect()->route('loans.dash')
                         ->with([
                            'message' => 'New Application Added Successfully',
                            'alert-type' => 'success'
                         ]);    
    }

    public function loanListsEdit($id)
    {
        $appli = LoanLists::findorfail($id);
        return response()->json([
            'status'=>200,
            'appli'=>$appli
        ]);
    }
    
    public function loanListsUpdate(Request $request)
    {
        $loan_id = $request->id;
        $min = 500;
        $days = 15;
        $valid = Validator::make($request->all(),[
            'borrower_id' => 'required',
            'shared_capital' => 'required',
            'plan_id' => 'required',
            'amount' => 'required',
            'purpose' => 'required',
            'status' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('loans.dash')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error'
                             ]);
        }
        if ($request->status == 2) {
            $roundoff = $request->amount / $min;
            for ($i=1; $i <= $roundoff ; $i++) { 
                $date = date('Y-m-d', strtotime(date('Y-m-d'). "+".$i*$days."days"));
                $check = LoanSchedules::where([
                    ['loan_id', '=' , $loan_id],
                    ['date_due', '=', $date],
                ])->count();

                if ($check > 0) {
                    LoanSchedules::findorfail($loan_id)->update([
                        'loan_id' => $loan_id,
                        'date_due' => $date,
                        'status' => 0,
                    ]);                    
                } else {
                    LoanSchedules::create([
                        'loan_id' => $loan_id,
                        'date_due' => $date,
                        'status' => 0,
                    ]);
                }
                
            }
        }
        LoanLists::findorfail($loan_id)->update([
            'borrower_id' => $request->borrower_id,
            'shared_capital' => $request->shared_capital,
            'plan_id' => $request->plan_id,
            'amount' => $request->amount,
            'amount_borrow' => $request->amount,
            'purpose' => $request->purpose,
            'date_released' => null,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('loans.dash')
                         ->with([
                            'message' => 'New Application Updated Successfully',
                            'alert-type' => 'success'
                         ]);    
    }
}