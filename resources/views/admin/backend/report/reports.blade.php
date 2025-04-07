@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Reports</h3>
        </div>
        <div class="card-body">
            <div class="col-md-4">
                <label for="">Month/Year</label>
                <input type="month" name="" id="" class="form-control">
            </div>
            <hr>
            <table class="table table-bordered" id="reportTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center align-middle" rowspan="2">#</th>
                        <th class="text-center align-middle" rowspan="2">Month Year</th>
                        @foreach ($plans as $plan)
                        <th class="text-center" colspan="2">{{ $plan->plan_name }}</th>
                        @endforeach
                        <th class="text-center" rowspan="2">Paid-In</th>
                        <th class="text-center" rowspan="2">Penalty</th>
                        <th class="text-center" rowspan="2">Total</th>
                    </tr>
                    <tr>
                        @foreach ($plans as $plan)
                        <th class="text-center">PRIN</th>
                        <th class="text-center">INT</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($payees as $borrower_id => $payee)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td>{{ $payee->first()->borrow }}</td>
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
                        <td class="text-center">{{ $payee->sum('tota_capital') ? number_format($payee->sum('tota_capital'),2 ) : '' }}</td>
                        <td class="text-center">
                            {{ number_format($payee->sum('paid') + $payee->sum('interest') + $payee->sum('tota_capital') + $payee->sum('penalty'),2 ) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection