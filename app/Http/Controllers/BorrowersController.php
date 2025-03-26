<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Borrower;
use Hash;

class BorrowersController extends Controller
{
    public function BorrowerDashboard()
    {
        return view('admin.backend.borrower.borrowers');
    }
}