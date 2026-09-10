<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoansController extends Controller
{
    public function LoansDashboard(){
        return inertia('Admin/Backend/Loans');
    }
}
