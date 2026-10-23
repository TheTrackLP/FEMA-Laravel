<?php

namespace App\Http\Controllers;

use App\Models\Borrowers;
use App\Models\Departments;
use App\Models\Loans;
use App\Models\LoanSchedules;
use App\Models\LoanTypes;
use App\Models\Payments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BorrowersController extends Controller
{
    private function StatusMessage(string $message, bool $success = true)
    {
        return redirect()->route('borrow.dash')->with($success ? 'success' : 'error', $message);
    }
    public function BorrowerDashboard(){
        return inertia('Admin/Backend/Borrowers', [
            'depts'=>Departments::all(),
            'borrows'=>Borrowers::select(
                                        '*',
                                        'departments.name'
                                )
                                ->join('departments', 'departments.id', '=', 'borrowers.deptid')
                                ->get(),
        ]);
    }

    public function BorrowerStore(Request $request){
        $currDate = date('currDate');
        $valid = Validator::make($request->all(), [
            'fullname' => 'required',
            'datebirth' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'empid' => 'required',
            'sharedcapital' => 'required',
            'deptid' => 'required',
            'yearservice' => 'required',
        ]);

        if($valid->fails()){
            return $this->StatusMessage('Error, Try Again', false);
        }

        Borrowers::create([
            'fullname' => $request->fullname,
            'datebirth' => $request->datebirth,
            'contact' => $request->contact,
            'address' => $request->address,
            'empid' => $request->empid,
            'sharedcapital' => $request->sharedcapital,
            'deptid' => $request->deptid,
            'yearservice' => $request->yearservice,
            'appliedat' => $currDate,
        ]);

        return $this->StatusMessage('Success, Borrower Updated', true);

    }

    public function BorrowerUpdate(Request $request){
        $currDate = date('Y-m-d');
        $valid = Validator::make($request->all(), [
            'fullname' => 'required',
            'datebirth' => 'required',
            'contact' => 'required',
            'address' => 'required',
            'empid' => 'required',
            'sharedcapital' => 'required',
            'deptid' => 'required',
            'yearservice' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('borrow.dash')->with(
                'error', 'Error, Try Again',
            );
        }

        Borrowers::findorfail($request->id)->update([
            'fullname' => $request->fullname,
            'datebirth' => $request->datebirth,
            'contact' => $request->contact,
            'address' => $request->address,
            'empid' => $request->empid,
            'sharedcapital' => $request->sharedcapital,
            'deptid' => $request->deptid,
            'yearservice' => $request->yearservice,
            'datejoined' => $currDate,
            'status' => $request->status,
        ]);

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Updated',
        );
    }

    public function BorrowerStatus($id){
        $borrowStat = Borrowers::findorfail($id);

        if($borrowStat->status == 1){
            $borrowStat->update([
                'status' => 2,
            ]);
        } else if($borrowStat->status == 2){
            $borrowStat->update([
                'status' => 1,
            ]);
        }

        return redirect()->route('borrow.dash')->with(
            'success', 'Success, Borrower Status Changed',
        );
    }

    public function BorrowerAccountDashboard(){
        $borrower_id = Auth::user()->borrower_id;

        return inertia('Borrower/Dashboard',[
            'loanTypes' => LoanTypes::all(),
            'profileData' => Borrowers::where('id', $borrower_id)->first(),
            'borrowerLoans' => Loans::select(
                DB::raw("SUM(loans.currbalance) as total_balance"),
                DB::raw("COUNT(loans.borrower_id) as total_loans"),
                DB::raw("SUM(payments.principal) as total_principal"),
                DB::raw("SUM(payments.interest) as total_interest"),
            )
            ->leftJoin('payments', 'payments.loan_id', '=', 'loans.id')
            ->where([
                ['loans.borrower_id', $borrower_id],
                ['loans.status', 2]
            ])
            ->first(),

            'listLoans' => Loans::select(
                'loans.*',
                'borrowers.fullname',
                'borrowers.sharedcapital',
                'borrowers.yearservice',
                'borrowers.datejoined',
                'loan_types.name as plan',
                'loan_types.interest_rate',
                DB::raw("CONCAT(loan_types.name, ' [',loan_types.interest_rate , '% interest, ', loan_types.penalty,'% penalty]') as fullplan"),
                DB::raw("SUM(payments.principal + interest) as total_paid")
            )
            ->leftJoin('borrowers', 'borrowers.id', '=', 'loans.borrower_id')
            ->leftJoin('loan_types', 'loan_types.id', '=', 'loans.loantype_id')
            ->leftJoin('payments', 'payments.loan_id', '=', 'loans.id')
            ->groupBy('loans.id')
            ->where('loans.borrower_id', $borrower_id)
            ->get(),
        ]);
    }

        public function NextPaymentDetails($id){
        $offset = Payments::where('loan_id', $id)->count();
        $schedule = LoanSchedules::select('date_due')
                ->where('loan_id', $id)
                ->orderby('date_due')
                ->offset($offset)
                ->limit(1)
                ->first();

        return response()->json([
            'schedule'=>$schedule,
            'offset'=>$offset,
        ]);
    }

    public function borrowerApplyLoanApplication(Request $request){
        $currDate = date('Y-m-d H:i:s');
        $valid = Validator::make($request->all(), [
            'borrower_id' => 'required',
            'loantype_id' => 'required',
            'amountborrowed' => 'required',
            'purpose' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('borrow.acct')->with(
                'error', 'Error, Try Again!',
            );
        }

        $year = now()->year;

        $lastLoan = Loans::where('refno', 'like', "FEMA-LOAN-{$year}%")
                        ->lockForUpdate()
                        ->orderByDesc('id')
                        ->first();
        
        $nextNumber = $lastLoan ? ((int) substr($lastLoan->refno, -5)) + 1 : 1;

        $invoice = "FEMA-LOAN-".$year."-". str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        Loans::create([
            'refno' => $invoice,
            'borrower_id' => $request->borrower_id,
            'loantype_id' => $request->loantype_id,
            'amountborrowed' => $request->amountborrowed,
            'currbalance' => $request->amountborrowed,
            'purpose' => $request->purpose,
            'date_applied' => $currDate,
        ]);

        return redirect()->route('borrow.acct')->with(
            'success', 'Success, Applied for Loan!',
        );
    }
     
    public function BorrowerLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
