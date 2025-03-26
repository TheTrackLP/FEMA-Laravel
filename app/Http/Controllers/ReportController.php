<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function ReportLoan()
    {
        return view('admin.backend.report.reports');
    }
}