<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoansController extends Controller
{
    public function LoansLists()
    {
        return view('admin.backend.loan.loans');
    }
}