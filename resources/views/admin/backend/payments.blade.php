@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="mt-3"></div>
    <div class="card">
        <div class="card-header">
            <button class="btn btn-primary btn-lg px-3 float-end" data-bs-toggle="modal" data-bs-target="#modalPayment"><i class="fa-solid fa-plus"></i> New Payments</button>
            <h3>Payments History</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="">Plan:</label>
                    <select name="" id="" class="form-select">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered" id="filterTable">
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
                    <td class="text-center">1</td>
                    <td>
                        <small><p>Loan Ref. #: <b>{{ $payee->loan_refno }}</b></p></small>
                        <small><p>OR. #: <b>{{ $payee->off_rec }}</b></p></small>
                    </td>
                    <td>
                        <p>Name: <b>{{ $payee->borrow }}</b></p>
                        <p>Plan: <b class="uppercase">{{ $payee->plan }}</b></p>
                    </td>
                    <td>
                        <p>Principal: <b>{{ number_format($payee->paid, 2) }}</b></p>
                        <p>Interest: <b>{{ number_format($payee->interest, 2) }}</b></p>
                        <p>Capital: <b>{{ number_format($payee->capital, 2) }}</b></p>
                    </td>
                    <td class="text-center">{{ number_format($payee->penalty, 2) }}</td>
                    <td class="text-center">{{ date('M d, Y', strtotime($payee->created_at)) }}</td>
                    <td class="text-center">
                        <button class="btn btn-outline-success btn-sm"><i class="fa-solid fa-print"></i></button>
                        <button class="btn btn-outline-success btn-sm"><i class="fa-solid fa-file"></i></button>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPayment" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{route('pay.add')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h4>Payment Form</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Name: | Plan:</label>
                        <select name="loan_id" id="loan_id" class="paymentSelect2">
                            <option value=""></option>
                            @foreach ($loans as $row)
                            <option value="{{ $row->id }}">{{ $row->borrow }} | {{ $row->plan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <hr class="my-5">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <input type="hidden" name="plan_id" id="plan">
                                <input type="hidden" name="borrower_id" id="borrower">
                                <label for="">OR. #:</label>
                                <input type="number" name="off_rec" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Remaining Balance:</label>
                                <input type="number" name="amount_balance" id="amount_balance" class="form-control" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Shared Capital:</label>
                                <input type="number" name="capital" id="capital" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col form-group mb-3">
                                    <label for="">Principal</label>
                                    <input type="number" name="paid" class="form-control">
                                </div>
                                <div class="col form-group mb-3">
                                    <label for="">Interest:</label>
                                    <input type="number" name="interest" class="form-control" step="any">
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Paid-in Capital:</label>
                                <input type="number" name="capital" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Penalty:</label>
                                <input type="number" name="penalty" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <p>Principal: <b></b></p>
                            </div>
                            <div class="form-group mb-3">
                                <p>Interest: <b></b></p>
                            </div>
                            <div class="form-group mb-3">
                                <p>Penalty: <b></b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-lg px-5">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#loan_id").change(function(){
            var loan_id = $(this).val();

            $.ajax({
                type: "GET",
                url: "/admin/payments/getLoan/" + loan_id,
                success: function(res){
                    console.log(res)
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