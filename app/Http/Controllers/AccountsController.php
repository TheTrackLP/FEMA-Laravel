<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use Validator;
use Hash;

class AccountsController extends Controller
{
    public function AllACcounts()
    {
        $staff_accts = User::select(
                            "users.*",
                            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrower"),
                            )
                            ->leftJoin('borrowers', 'borrowers.id', '=', 'users.borrower_id')
                            ->where('users.roles', 'admin')->orWhere("users.borrower_id", 0)->get();

        $borrow_accts = User::select(
                            "users.*",
                            DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as borrower"),
                            )
                            ->join('borrowers', 'borrowers.id', '=', 'users.borrower_id')
                            ->where('users.roles', 'borrower')->get();

        return view('admin.backend.accts.accounts', compact('staff_accts', 'borrow_accts'));
    }

    public function GetAccountInfo($id)
    {
        $acct = User::select(
                    'users.*',
                    DB::raw("CONCAT(borrowers.lastname, ', ', borrowers.firstname, ' ', borrowers.middlename) as acct_name"),
                    )
                    ->leftJoin('borrowers', 'borrowers.id', '=', 'users.borrower_id')
                    ->where("users.id", $id)
                    ->first();
        return response()->json([
           'status'=>200,
           'acct'=>$acct, 
        ]);
    }

    public function AccountUpdate(Request $request)
    {
        $acct_id = $request->id;
        $valid = Validator::make($request->all(),[
            'username' => 'required',
            'email' => 'required',
            'status' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route("accts")
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error',
                             ]);
        }
        if ($request->password != $request->confirm_password) {
            return redirect()->route('accts')
            ->with([
                'message' => "Your Password doesn't Match",
                'alert-type' => 'error'
            ]);
        }

        User::findorfail($acct_id)->update([
            'username' => $request->username,
            'email' => $request->email,
            'status' => $request->status,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('accts')
                ->with([
                    'message' => "Account Updated Successfully",
                    'alert-type' => 'success',
        ]);
    }
}
