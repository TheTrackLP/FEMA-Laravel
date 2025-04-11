<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<style>
    @media print{
        @page{
            size: landscape;
        }
        .table-bordered{
            border: 1px black solid !important;
        }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
<body>
    <div class="container-fluid">
        <table class="table table-bordered">
            <thead class="table-info table-bordered">
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
            <tbody>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>