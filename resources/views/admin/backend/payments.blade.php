@extends('admin.body.header')
@section('admin')
@php
$curr_date = date('F j, Y, g:i a');
$i = 1;
@endphp
<div class="container-fluid">
    <div class="mt-3"></div>
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary btn-lg px-3 float-end" data-bs-toggle="modal"
                data-bs-target="#modalPayment"><i class="fa-solid fa-plus"></i> New Payments</button>
            <h3>Payments History</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <label for="">Plan:</label>
                    <select name="" id="paymentPlans" class="form-select select2">
                        <option value=""></option>
                        @foreach ($plans as $plan)
                        <option value="{{ $plan->plan_name }}">{{ $plan->plan_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered" id="paymentsTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Reference and CV#</th>
                        <th class="text-center">Borrower's Details</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Penalty</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                @foreach ($payees as $payee)
                <tr>
                    <td class="text-center align-middle">{{ $i++ }}</td>
                    <td class="align-middle">
                        <small>
                            <p>Loan Ref. #: <b>{{ $payee->loan_refno }}</b></p>
                        </small>
                        <small>
                            <p>OR. #: <b>{{ $payee->off_rec }}</b></p>
                        </small>
                    </td>
                    <td class="align-middle">
                        <p>Name: <b>{{ $payee->borrow }}</b></p>
                        <p>Plan: <b class="uppercase">{{ $payee->plan }}</b></p>
                    </td>
                    <td class="align-middle">
                        <p>Principal: <b>{{ number_format($payee->paid, 2) }}</b></p>
                        <p>Interest: <b>{{ number_format($payee->interest, 2) }}</b></p>
                        <p>Capital: <b>{{ number_format($payee->capital, 2) }}</b></p>
                    </td>
                    <td class="text-center align-middle">{{ number_format($payee->penalty, 2) }}</td>
                    <td class="text-center align-middle">{{ date('M d, Y', strtotime($payee->created_at)) }}</td>
                    <td class="text-center align-middle">
                        <a class="btn btn-outline-success btn-sm" href="{{ route('print.receipt', $payee->id) }}"><i
                                class="fa-solid fa-print"></i></a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPayment" tabindex="-1" aria-labelledby="modalPaymentLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <form action="{{ route('pay.add') }}" method="POST" id="myForm">
                @csrf
                <div class="modal-header bg-success bg-opacity-10 border-0">
                    <h4 class="modal-title fw-bold text-success" id="modalPaymentLabel">
                        <i class="bi bi-credit-card me-2"></i> Payment Form
                    </h4>
                    <h3>{{ $curr_date }}</h3>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Select Borrower & Plan</label>
                        <select name="loan_id" id="loan_id" class="form-select paymentSelect2 @error('loan_id')
                        @enderror" required>
                            <option value="">Choose a borrower...</option>
                            @foreach ($loans as $row)
                            <option value="{{ $row->id }}">{{ $row->borrow }} | {{ $row->plan }}</option>
                            @endforeach
                        </select>
                        @error('loan_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <hr class="my-4">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <input type="hidden" name="plan_id" id="plan">
                            <input type="hidden" name="borrower_id" id="borrower">
                            <div class="form-group mb-3">
                                <label class="form-label">Official Receipt #</label>
                                <input type="number" name="off_rec" class="form-control @error('off_rec')
                                @enderror" placeholder="Enter OR number" required>
                                @error('off_rec')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Remaining Balance</label>
                                <input type="number" name="amount_balance" id="amount_balance" class="form-control"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Shared Capital</label>
                                <input type="number" name="capital" id="capital" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="form-group col mb-3">
                                    <label class="form-label">Principal</label>
                                    <input type="number" name="paid" class="form-control" placeholder="₱" step="any">
                                </div>
                                <div class="form-group col mb-3">
                                    <label class="form-label">Interest</label>
                                    <input type="number" name="interest" class="form-control" placeholder="₱"
                                        step="any">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Paid-in Capital</label>
                                <input type="number" name="capital_paid" class="form-control" placeholder="₱"
                                    step="any">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Penalty</label>
                                <input type="number" name="penalty" class="form-control" placeholder="₱" step="any">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light rounded p-3 h-100">
                                <h6 class="fw-bold text-muted mb-3">Summary</h6>
                                <p class="mb-2">Principal: <b id="summaryPrincipal">₱0.00</b></p>
                                <p class="mb-2">Interest: <b id="summaryInterest">₱0.00</b></p>
                                <p class="mb-2">Penalty: <b id="summaryPenalty">₱0.00</b></p>
                                <hr>
                                <p class="fw-bold mb-0">Total: <span id="summaryTotal">₱0.00</span></p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="bg-light rounded p-3 h-100">
                                <h6 class="fw-bold mb-3 text-primary">Today’s Expected Payments</h6>
                                <p class="mb-2">Principal: <b id="expectedPrincipal">₱</b></p>
                                <p class="mb-2">Interest: <b id="expectedInterest">₱</b></p>
                                <p class="mb-2">Penalty: <b id="expectedPenalty">₱</b></p>
                                <hr>
                                <p class="fw-bold mb-0">Total: <span id="summaryTotal2">₱0.00</span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-5">
                        <i class="bi bi-save me-1"></i> Save Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

    $('#myForm').validate({
        rules: {
            loan_id: {
                required: true,
            },
            off_rec: {
                required: true,
            },
            paid: {
                required: true,
            },
            interest: {
                required: function() {
                    if (day => 1 && day <= 15) {
                        return true;
                    } else {
                        return false
                    }
                }
            },
        },
        messages: {
            loan_id: {
                required: 'Please Select Borrower',
            },
            off_rec: {
                required: 'Please Enter OR #',
            },
            paid: {
                required: 'Please Enter Principal',
            },
            interest: {
                required: 'Please Enter Interest',
            },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});

const inputs = document.querySelectorAll('[name="paid"], [name="interest"], [name="penalty"]');
inputs.forEach(input => {
    input.addEventListener('input', () => {
        const principal = parseFloat(document.querySelector('[name="paid"]').value) || 0;
        const interest = parseFloat(document.querySelector('[name="interest"]').value) || 0;
        const penalty = parseFloat(document.querySelector('[name="penalty"]').value) || 0;
        const total = principal + interest + penalty;
        document.getElementById('summaryPrincipal').textContent = `₱${principal.toFixed(2)}`;
        document.getElementById('summaryInterest').textContent = `₱${interest.toFixed(2)}`;
        document.getElementById('summaryPenalty').textContent = `₱${penalty.toFixed(2)}`;
        document.getElementById('summaryTotal').textContent = `₱${total.toFixed(2)}`;
    });
});
$(document).ready(function() {
    $("#loan_id").change(function() {
        var loan_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/admin/payments/getLoan/" + loan_id,
            success: function(res) {
                var principal2 = 500;
                var amount = res.loanD.amount;
                var interest2 = res.loanD.interest_percentage;
                var penalty2 = res.loanD.penalty_rate;
                const curr_date = new Date();
                var with_interest = (amount * interest2 / 100);
                var penalty_rate = (amount * penalty2 / 100);
                var overdue_date = res.loanD.date_due;

                var grand_total = 0;
                const formattedDate = curr_date.toISOString().split('T')[0];
                if (curr_date.getDate() >= 1 && curr_date.getDate() <= 15) {
                    if (formattedDate > overdue_date) {
                        $("#expectedPenalty").text(penalty2);
                        grand_total = principal2 + with_interest + penalty_rate;
                    }
                    grand_total = principal2 + with_interest;
                    $("#paid").val(principal2);
                    $("#expectedPrincipal").text(principal2);
                } else if (curr_date.getDate() >= 16 && curr_date.getDate() <= 31) {
                    if (formattedDate > overdue_date) {
                        grand_total = principal2 + with_interest + penalty_rate;
                    }
                    grand_total = principal2 + with_interest;
                    $("#expectedPrincipal").text(principal2);
                    $("#expectedInterest").text(with_interest);
                }
                document.getElementById('summaryTotal2').textContent =
                    `₱${grand_total.toFixed(2)}`;
                $("#amount_balance").val(res.loanD.amount);
                $("#plan").val(res.loanD.plan_id);
                $("#capital").val(res.loanD.shared_capital);
                $("#borrower").val(res.loanD.borrower_id);
            }
        });
    })
});
</script>
@endsection