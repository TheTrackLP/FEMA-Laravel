<?php

namespace App\Http\Controllers;

use App\Models\LoanTypes;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{
    public function ReportsDashboard(){
        return inertia('Admin/Backend/Reports', [
            'loanTypes' => LoanTypes::all(),
            'payees' => Payments::select(
                'payments.borrower_id',
                'payments.type_id',
                'borrowers.fullname',
                'loan_types.name as type_name',
                DB::raw("DATE_FORMAT(payments.created_at, '%Y-%m') as month"),
                DB::raw("SUM(payments.capital) as total_capital"),           
                DB::raw("SUM(payments.principal) as total_paid"),           
                DB::raw("SUM(payments.interest) as total_interest"),           
                DB::raw("SUM(payments.penalty) as total_penalty"),       
                DB::raw("IFNULL(SUM(payments.capital), 0) + IFNULL(SUM(payments.principal), 0) + IFNULL(SUM(payments.interest), 0) + IFNULL(SUM(payments.penalty), 0) as overalltotal"),       
            )
            ->join('borrowers', 'borrowers.id', '=', 'payments.borrower_id')
            ->join('loan_types', 'loan_types.id', '=', 'payments.type_id')
            ->groupBy('payments.borrower_id', 'payments.type_id', 'borrowers.fullname', 'loan_types.name', DB::raw("DATE_FORMAT(payments.created_at, '%Y-%m')"))
            ->get()
            ->groupBy(fn ($date) => $date->borrower_id . '-' . $date->month)
            ->map(fn ($rows) => [
                'borrower_id' => $rows->first()->borrower_id,
                'month' => $rows->first()->month,
                'fullname' => $rows->first()->fullname,
                'total_capital' => $rows->sum('total_capital'),
                'total_paid' => $rows->sum('total_paid'),
                'total_interest'=> $rows->sum('total_interest'),
                'total_penalty'=> $rows->sum('total_penalty'),
                'overalltotal'=> $rows->sum('overalltotal'),
                'loans' => $rows->groupBy('type_id')->map(fn ($r) => [
                    'principal' => $r->sum('total_paid'),
                    'interest' => $r->sum('total_interest'),
                ]),
            ])
            ->values(),
        ]);
    }

    public function PrintReportDate(Request $request){
            $reports = Payments::select(
                'payments.borrower_id',
                'payments.type_id',
                'borrowers.fullname',
                'loan_types.name as type_name',
                DB::raw("SUM(payments.capital) as total_capital"),           
                DB::raw("SUM(payments.principal) as total_paid"),           
                DB::raw("SUM(payments.interest) as total_interest"),           
                DB::raw("SUM(payments.penalty) as total_penalty"),       
                DB::raw("IFNULL(SUM(payments.capital), 0) + IFNULL(SUM(payments.principal), 0) + IFNULL(SUM(payments.interest), 0) + IFNULL(SUM(payments.penalty), 0) as overalltotal"),       
            )
            ->join('borrowers', 'borrowers.id', '=', 'payments.borrower_id')
            ->join('loan_types', 'loan_types.id', '=', 'payments.type_id')
            ->groupBy('payments.borrower_id', 'payments.type_id', 'borrowers.fullname', 'loan_types.name')
            ->whereRaw("DATE_FORMAT(payments.created_at, '%Y-%m') = ?", [$request->report_date])
            ->get()
            ->groupBy(fn ($date) => $date->borrower_id . '-' . $date->month)
            ->map(fn ($rows) => [
                'borrower_id' => $rows->first()->borrower_id,
                'fullname' => $rows->first()->fullname,
                'total_capital' => $rows->sum('total_capital'),
                'total_paid' => $rows->sum('total_paid'),
                'total_interest'=> $rows->sum('total_interest'),
                'total_penalty'=> $rows->sum('total_penalty'),
                'overalltotal'=> $rows->sum('overalltotal'),
                'loans' => $rows->groupBy('type_id')->map(fn ($r) => [
                    'principal' => $r->sum('total_paid'),
                    'interest' => $r->sum('total_interest'),
                ]),
            ])
            ->values();
        return inertia('Admin/Backend/PrintReport', [
            'reports' => $reports,
            'filters' => $request->only(['report_date']),
            'loanTypes' => LoanTypes::all(),
        ]);

    }
}
