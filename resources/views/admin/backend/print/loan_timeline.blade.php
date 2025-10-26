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

<body>
    <div class="container-fluid my-4">
        <div class="offset-md-1 col-md-10 card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ strtoupper($loan->plan) }}</h5>
                    <h4><span class="badge bg-light text-dark">Borrower: <strong>{{ $loan->borrower }}</strong></span>
                    </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>#</th>
                                <th>Date Due</th>
                                <th>Principal</th>
                                <th>Interest</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach ($timeline as $time)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ date('F j, Y',strtotime($time->date_due)) }}</td>
                                <td>{{ $time->amount }}</td>
                                <td>{{ $time->interest }}</td>
                                <td>Total Amount</td>
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
                    </table>
                </div>

                <div class="mt-3 text-end">
                    <p><strong>Total Principal:</strong> ₱9,000.00</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>