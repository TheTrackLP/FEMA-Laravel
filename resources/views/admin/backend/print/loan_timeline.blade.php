@php
$i = 1;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Timeline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>

<body>
    <div class="container my-4">
        <div class="offset-md-1 col-md-10 card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ url()->previous() }}" class="btn btn-light btn-sm me-3 no-print">
                        ← Back
                    </a>
                    <h4 class="mb-0">
                        <span class="badge bg-light text-dark">
                            {{ strtoupper($loan->plan) }}
                        </span>
                    </h4>
                </div>
                <h5 class="mb-0">
                    <span class="badge bg-light text-dark">
                        Borrower: <strong>{{ $loan->borrower }}</strong>
                    </span>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Date Due</th>
                                <th>Principal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach ($timeline as $time)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ date('F j, Y',strtotime($time->date_due)) }}</td>
                                <td>₱500.00</td>
                                <td>
                                    @if($time->status == 0)
                                    <span class="badge rounded-pill bg-danger">Not yet Paid</span>
                                    @elseif($time->status == 1)
                                    <span class="badge rounded-pill bg-success">Paid</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light text-center">
                            <tr>
                                <th colspan="2">Original Loan Amount</th>
                                <th colspan="2">₱{{ number_format($loan->amount_borrow, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Total Principal Paid</th>
                                <th colspan="2">₱{{ number_format($totalPaid, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">Remaining Loan Balance</th>
                                <th colspan="2">₱{{ number_format($loan->amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>