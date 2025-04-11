<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\LoanLists;
use App\Models\Borrower;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        $for_approval = LoanLists::where('status', 0)->count();
        $approved = LoanLists::where('status', 1)->count();
        $active = LoanLists::where('status', 2)->count();
        $denied = LoanLists::where('status', 3)->count();
        $complete = LoanLists::where('status', 4)->count();

        $pending = Borrower::where('status', 0)->count();
        $curr_members = Borrower::where('status', 1)->count();
        return view('admin.dashboard', compact('for_approval', 'approved' ,'active', 'denied', 'complete', 'pending', 'curr_members'));
    }

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}