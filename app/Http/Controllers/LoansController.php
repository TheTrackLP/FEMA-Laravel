<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use Illuminate\Http\Request;

class LoansController extends Controller
{
    public function LoansDashboard(){
        return inertia('Admin/Backend/Loans', [
            'borrowers'=>Borrowers::all(),
        ]);
    }
}
