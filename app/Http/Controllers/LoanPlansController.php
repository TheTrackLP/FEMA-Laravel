<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;
use Carbon\Carbon;
use App\Models\LoanPlans;

class LoanPlansController extends Controller
{
    public function LoanPlanLists()
    {
        $plans = LoanPlans::all();
        return view('admin.backend.loan_plans.plans', compact('plans'));
    }

    public function AddLoanPlan(Request $request)
    {
        $valid = Validator::make($request->all(),[
            'plan_name' => 'required',
            'interest_percentage' => 'required',
            'penalty_rate' => 'required',
            'plan_desc' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('plan.list')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again',
                                'alert-type' => 'error',
                             ]);
        }

        LoanPlans::create($request->all());

        return redirect()->route('plan.list')
                         ->with([
                            'message' => 'Loan Plan Added Successfully',
                            'alert-type' => 'success',
                         ]);
    }

    public function LoanPlanEdit($id)
    {
        $plan = LoanPlans::findorfail($id);

        return response()->json([
            'status'=>200,
            'plan'=>$plan,
        ]);
    }

    public function LoanPlanUpdate(Request $request)
    {
        $plan_id = $request->id;
        $valid = Validator::make($request->all(),[
            'plan_name' => 'required',
            'interest_percentage' => 'required',
            'penalty_rate' => 'required',
            'plan_desc' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('plan.list')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again',
                                'alert-type' => 'error',
                             ]);
        }

        LoanPlans::findorfail($plan_id)->update([
            'plan_name' => $request->plan_name,
            'interest_percentage' => $request->interest_percentage,
            'penalty_rate' => $request->penalty_rate,
            'plan_desc' => $request->plan_desc,
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('plan.list')
                         ->with([
                            'message' => 'Loan Plan Updated Successfully',
                            'alert-type' => 'success',
                         ]);
    }

    public function LoanPlanDelete($id)
    {
        LoanPlans::findorfail($id)->delete();
        return redirect()->route('plan.list')
                         ->with([
                            'message' => 'Loan Plan Delete Successfully',
                            'alert-type' => 'warning',
                         ]);
    }
}