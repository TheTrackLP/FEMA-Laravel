<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Borrower;
use App\Models\Departments;
use Hash;
use Validator;
use Carbon\Carbon;
use Auth;
use App\Models\User;
use App\Models\LoanPlans;
use App\Models\LoanLists;

class BorrowersController extends Controller
{
    public function BorrowerLists()
    {
        $borrowers = DB::table('borrowers')
                            ->select(
                                'borrowers.*',
                                DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.lastname) as borrower"),
                                DB::raw("CONCAT(departments.dept_name) as dept"),
                            )
                            ->join('departments', 'departments.id', '=', 'borrowers.dept_id')
                            ->get();
        $depts = Departments::all();
        return view('admin.backend.borrowers', compact('borrowers', 'depts'));
    }

    public function BorrowerDetails($id)
    {
        $borrow_id = Borrower::findorfail($id);

        return response()->json([
            'status'=>200,
            'borrower'=>$borrow_id,
        ]);
    }

    public function BorrowerUpdate(Request $request){
        $borrow_id = $request->id;

        $valid = Validator::make($request->all(), [
            'firstname'  => 'required',
            'lastname' => 'required',
            'date_birth' => 'required',
            'contact_no' => 'required',
            'address' => 'required',
            'emp_id' => 'required',
            'shared_capital' => 'required',
            'dept_id' => 'required',
            'years_service' => 'required',
            'status' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('borrow.list')
                              ->withErrors($valid)
                                ->with([
                                    'message' => 'Error, Try Again',
                                    'alert-type' => 'error',
                                ]);
        }

        Borrower::findorfail($borrow_id)->update([
            'firstname'  => $request->firstname,
            'middlename'  => $request->middlename,
            'lastname' => $request->lastname,
            'date_birth' => $request->date_birth,
            'contact_no' => $request->contact_no,
            'address' => $request->address,
            'emp_id' => $request->emp_id,
            'shared_capital' => $request->shared_capital,
            'dept_id' => $request->dept_id,
            'years_service' => $request->years_service,
            'status' => $request->status,
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('borrow.list')
                       ->with([
                        'message' => 'Borrower Updated Successfully',
                        'alert-type' => 'success',
                    ]);   
    }

    public function BorrowerDelete($id)
    {
        Borrower::findorfail($id)->delete();
        return redirect()->route('borrow.list')
                       ->with([
                        'message' => 'Borrower Delete Successfully',
                        'alert-type' => 'warning',
                    ]);   
    }

    public function BorrowerDashboard()
    {
        $id = Auth::user()->id;
        $borrower_id = Auth::user()->borrower_id;
        $profileData = User::select(
            'users.*',
            'borrowers.*',
            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrower"),
            )
            ->leftJoin('borrowers', 'borrowers.id', '=', 'users.borrower_id')
            ->where('users.id', $id)
            ->first();
        
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
            ->where('loan_lists.borrower_id', $borrower_id)
            ->get();

        $plans = LoanPlans::all();
        return view('borrower.dashboard', compact('profileData', 'loans', 'plans'));
    }

    public function BorrowerApplyLoan(Request $request)
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
            return redirect()->route('borrow.dash')
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

        return redirect()->route('borrow.dash')
                         ->with([
                            'message' => 'New Application Added Successfully',
                            'alert-type' => 'success'
                         ]);    
    }

    public function BorrowerLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}