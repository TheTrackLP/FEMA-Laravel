@extends('admin.body.header')
@section('admin')

@php
    $curr_month = date("F");
@endphp

<style>
.separator {
  display: flex;
  align-items: center;
  text-align: center;
}

.separator::before,
.separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #000;
}

.separator:not(:empty)::before {
  margin-right: .25em;
}

.separator:not(:empty)::after {
  margin-left: .25em;
}
</style>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Reports</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end g-2">
                <div class="col-md-4">
                    <label for="monthly" class="form-label fw-bold">Month/Year</label>
                    <input type="month" name="date_month" id="date_month" class="form-control">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success w-100" type="button" onclick="PrintMonthReport()" id="print">
                        <i class="fa fa-print me-1"></i> Print Report
                    </button>
                </div>
            </div>            
            <div class="separator py-4">This Month Payment History: &nbsp;<b>{{ $curr_month }}</b></div>
            <table class="table table-bordered">
                <thead class="table-info">
                    <tr>
                        <th class="text-center align-middle" rowspan="2">#</th>
                        <th class="text-center align-middle" rowspan="2">Borrower Name</th>
                        @foreach ($plans as $plan)
                        <th class="text-center" colspan="2">{{ $plan->plan_name }}</th>
                        @endforeach
                        <th class="text-center align-middle" rowspan="2">Paid-In</th>
                        <th class="text-center align-middle" rowspan="2">Penalty</th>
                        <th class="text-center align-middle" rowspan="2">Total</th>
                    </tr>
                    <tr>
                        @foreach ($plans as $plan)
                        <th class="text-center">PRIN</th>
                        <th class="text-center">INT</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="reportBody">
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($payees as $borrower_id => $payee)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td>{{ $payee->first()->borrow }}
                        </td>
                        @foreach ($plans as $plan)
                        @php
                            $payments = $payee->firstWhere("plan_id", $plan->id);
                        @endphp
                        @if ($payments)
                            <td class="text-center">{{ number_format($payments->paid,2 ) }}</td>
                            <td class="text-center">{{ $payments->interest ? number_format($payments->interest,2 ) : '' }}</td>
                        @else
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        @endif
                        @endforeach
                        <td class="text-center">{{ $payee->sum('penalty') ? number_format($payee->sum('penalty'),2 ) : ''}}</td>
                        <td class="text-center">{{ $payee->sum('total_capital') ? number_format($payee->sum('total_capital'),2 ) : '' }}</td>
                        <td class="text-center">
                            {{ number_format($payee->sum('paid') + $payee->sum('interest') + $payee->sum('total_capital') + $payee->sum('penalty'),2 ) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function PrintMonthReport(){
    const selectedDate = $("#date_month").val();
    if(!selectedDate){
        toastr.error("Error, Pick a Month first!");
        return;
    }
    const url = `{{ route('report.date') }}?date_month=${selectedDate}`;
    printWindow(url);
}

function printWindow(url){
    const print = window.open(url, '_blank');
    print.addEventListener('load', function() {
        print.print();
    }, true);
}
</script>

@endsection