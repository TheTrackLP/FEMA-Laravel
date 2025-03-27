<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Borrower;
use App\Models\User;
use Validator;
use Hash;
use Carbon\Carbon;

class RegisteredBorrowerController extends Controller
{
    public function RegisterPage()
    {
        return view('auth.register');
    }

    public function RegisterBorrower(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'firstname'  => 'required',
            'middlename'  => 'required',
            'lastname' => 'required',
            'date_birth' => 'required',
            'contact_no' => 'required',
            'address' => 'required',
            'emp_id' => 'required',
            'shared_capital' => 'required',
            'dept_id' => 'required',
            'years_service' => 'required',
        ]);

        $AuthValid = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        if($valid->fails()) {
            return redirect()->route('register')
                            ->withErrors($valid)
                                ->with([
                                    'message' => 'Error, Try Again!',
                                    'alert-type' => 'error'
                                ]);
        }
        
        if ($AuthValid->fails()) {
            return redirect()->route('register')
                                ->withErrors($AuthValid)
                                ->with([
                                    'message' => 'Fill up your Credentials',
                                    'alert-type' => 'error'
                                ]);
        }

        if ($request->password != $request->confirm_password) {
            return redirect()->route('register')
            ->withErrors($AuthValid)
            ->with([
                'message' => "Your Password doesn't Match",
                'alert-type' => 'error'
            ]);
        }

        $borrower_id = Borrower::insertGetId([
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
            'created_at' => Carbon::now(),
        ]);

        User::create([
            'username' => $request->username,
            'borrower_id' => $borrower_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => 'borrower',
        ]);

        return redirect()->route('login')->with([
            'message' => 'Borrower Registered, Login your Credentials',
            'alert-type' => 'info'
        ]);
    }
}
