<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BorrowersController extends Controller
{
    public function BorrowerDashboard(){
        return inertia('Admin/Backend/Borrowers', [
            'depts'=>Departments::all(),
            'borrows'=>Borrowers::select(
                                        '*',
                                        'departments.name'
                                )
                                ->join('departments', 'departments.id', '=', 'borrowers.deptid')
                                ->get(),
        ]);
    }

    public function BorrowerStore(Request $request){
        $valid = Validator::make($request->all(), [
            'fullname' => 'required',
            'datebirth' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'empid' => 'required',
            'sharedcapital' => 'required',
            'deptid' => 'required',
            'yearservice' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('borrow.dash')->with(
                'error', 'Error, Try Again',
            );
        }

        Borrowers::create([
            'fullname' => $request->fullname,
            'datebirth' => $request->datebirth,
            'contact' => $request->contact,
            'address' => $request->address,
            'empid' => $request->empid,
            'sharedcapital' => $request->sharedcapital,
            'deptid' => $request->deptid,
            'yearservice' => $request->yearservice,
        ]);

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Added',
        );
    }

    public function BorrowerUpdate(Request $request){
        $valid = Validator::make($request->all(), [
            'fullname' => 'required',
            'datebirth' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'empid' => 'required',
            'sharedcapital' => 'required',
            'deptid' => 'required',
            'yearservice' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('borrow.dash')->with(
                'error', 'Error, Try Again',
            );
        }

        Borrowers::findorfail($request->id)->update([
            'fullname' => $request->fullname,
            'datebirth' => $request->datebirth,
            'contact' => $request->contact,
            'address' => $request->address,
            'empid' => $request->empid,
            'sharedcapital' => $request->sharedcapital,
            'deptid' => $request->deptid,
            'yearservice' => $request->yearservice,
            'appliedat' => $request->yearservice,
        ]);

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Updated',
        );
    }
}
