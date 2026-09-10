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
        $currDate = date('currDate');
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
            'appliedat' => $currDate,
        ]);

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Added',
        );
    }

    public function BorrowerUpdate(Request $request){
        $currDate = date('Y-m-d');
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
            'datejoined' => $currDate,
            'status' => $request->status,
        ]);

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Updated',
        );
    }

    public function BorrowerStatus($id){
        $borrowStat = Borrowers::findorfail($id);

        if($borrowStat->status == 1){
            $borrowStat->update([
                'status' => 2,
            ]);
        } else if($borrowStat->status == 2){
            $borrowStat->update([
                'status' => 1,
            ]);
        }

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Status Changed',
        );
    }
}
