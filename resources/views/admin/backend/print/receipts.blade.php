@php
$curr_date = date('F j, Y, g:i a');
$grand_total = $payee->paid + $payee->interest + $payee->capital + $payee->penalty;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }

    td,
    p {
        margin: 0;
    }

    .receipt {
        max-width: 700px;
        margin: 30px auto;
        background: #fff;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .receipt-header {
        border-bottom: 2px solid #0d6efd;
        margin-bottom: 20px;
        padding-bottom: 15px;
    }

    .receipt-header h2 {
        color: #0d6efd;
        margin-bottom: 0;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .total {
        font-size: 1.1rem;
        font-weight: bold;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="receipt-header text-center">
            <h2>FEMA Loan Management System</h2>
            <p class="text-muted mb-0">Official Payment Receipt</p>
        </div>
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <div>
                    <p><strong>Receipt No:</strong> {{ $payee->off_rec }}</p>
                    <p><strong>Date:</strong> {{ $curr_date }}</p>
                </div>
                <div class="text-end">
                    <p><strong>Received From:</strong> {{ strtoupper($payee->borrower) }}</p>
                </div>
            </div>
        </div>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Description</th>
                    <th class="text-end">Amount (₱)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>{{ strtoupper($payee->plan_name) }}</strong></td>
                    <td class="">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Principal:</strong>
                                <span class="float-end fw-bold">₱{{ number_format($payee->paid, 2) }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Interest:</strong>
                                <span class="float-end fw-bold">₱{{ number_format($payee->interest,2 ) }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Shared Capital:</strong>
                                <span class="float-end fw-bold">₱{{ number_format($payee->capital, 2) }}</span>
                            </li>
                            <li class="mb-2">
                                <strong>Penalty:</strong>
                                <span class="float-end fw-bold">₱{{ number_format($payee->penalty,2 ) }}</span>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="1" class="text-end total">Total Paid</td>
                    <td class="text-end total">₱{{ number_format($grand_total,2) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4">
            <p><strong>Received by:</strong> {{ strtoupper(Auth::user()->username) }}</p>
            <p class="text-muted">Thank you for your payment.</p>
        </div>
        <div class="text-center mt-4 no-print">
            <a class="btn btn-danger" href="{{ url()->previous() }}">Back</a>
            <button class="btn btn-primary" onclick="window.print()">🖨️ Print Receipt</button>
        </div>
    </div>
</body>

</html>