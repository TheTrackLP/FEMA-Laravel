<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountsController extends Controller
{
    public function AccountsDashboard(){
        return inertia('Admin/Backend/Accounts',[
            'accounts'=>User::select(
                'users.*',
                'borrowers.fullname',
                'borrowers.datejoined'
            )
            ->leftJoin('borrowers', 'borrowers.id', '=', 'users.borrower_id')
            ->get(),
            'borrowers'=>Borrowers::all(),
        ]);
    }

    public function AccountsCreation(Request $request){
        $valid = Validator::make($request->all(), [
            'email' => 'required',
            'username' => 'required',
            'roles' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('accts.dash')->with(
                'error', 'Error, Try Again!',
            );
        }

        if($request->password != $request->confirmPass){
            return redirect()->route('accts.dash')->with(
                'error', 'Error, Password Does not Match!',
            );
        }

        User::create([
            'borrower_id' => $request->borrower_id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'roles' => $request->roles,
            'status' => $request->status,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('accts.dash')->with(
            'success', 'Success, Account Created!',
        );
    }

    public function AccountsUpdate(Request $request){
        $valid = Validator::make($request->all(), [
            'email' => 'required',
            'username' => 'required',
            'roles' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('accts.dash')->with(
                'error', 'Error, Try Again!',
            );
        }

        if($request->password != $request->confirmPass){
            return redirect()->route('accts.dash')->with(
                'error', 'Error, Password Does not Match!',
            );
        }

        if($request->filled('password')){
            User::findorfail($request->id)->update([
                'borrower_id' => $request->borrower_id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'roles' => $request->roles,
                'status' => $request->status,
                'password' => Hash::make($request->password),
            ]);
            } else {
            User::findorfail($request->id)->update([
                'borrower_id' => $request->borrower_id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'roles' => $request->roles,
                'status' => $request->status,
            ]);
        }


        return redirect()->route('accts.dash')->with(
            'success', 'Success, Account Updated!',
        );
    }
}
