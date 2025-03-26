<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegisteredBorrowerController extends Controller
{
    public function RegisterPage()
    {
        return view('auth.register');
    }

    public function RegisterBorrower()
    {
        $valid = Validator::make($request->all(), [

        ]);
    }
}
