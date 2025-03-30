<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Borrower;
use App\Models\Departments;
use Hash;
use Validator;
use Carbon\Carbon;

class BorrowersController extends Controller
{
    public function BorrowerDashboard()
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
        return view('admin.backend.borrower.borrowers', compact('borrowers', 'depts'));
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
}