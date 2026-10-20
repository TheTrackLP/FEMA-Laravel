<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Loans;
use App\Models\LoanTypes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard(){
        return inertia('Admin/Dashboard', [
            'totalBorrowers' => Borrowers::count(),
            'totalActiveBorrowers' => Borrowers::where('status', 1)->count(),
            'totalPendingLoans' => Loans::where([
                ['status', 0],
                ['status', 1],
            ])->count(),
            'totalLoanTypes' => LoanTypes::count(),
        ]);
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
