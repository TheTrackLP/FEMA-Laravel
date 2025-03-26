<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoanPlansController extends Controller
{
    public function LoanPlanLists()
    {
        return view('admin.backend.loan_plans.plans');
    }
}