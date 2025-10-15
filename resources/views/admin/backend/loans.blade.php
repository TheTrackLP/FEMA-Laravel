@extends('admin.body.header')
@section('admin')

<style>
.radio {
    font-size: 23px;
}
</style>
<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-end" id="clear" data-bs-toggle="modal"
                data-bs-target="#addApplication"><i class="fa-solid fa-plus"></i> Create New Application</button>
            <h3>Loan Lists</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="">Type of Loan</label>
                    <select name="fPlan" id="fPlan" class="form-select select2">
                        <option value=""></option>
                        @foreach ($plans as $plan)
                        <option value="{{ $plan->plan_name }}">{{ $plan->plan_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="">Status</label>
                    <select name="fStatus" id="fStatus" class="form-select select2">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered" id="filterTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">CV#</th>
                        <th class="text-center">Borrower's Details</th>
                        <th class="text-center">Amount Details</th>
                        <th class="text-center">Next Payment Details</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach ($loans as $loan)
                    @php
                    $offset = App\Models\Payments::where('loan_id', $loan->id)->count();
                    $next = App\Models\LoanSchedules::select('date_due')->where('loan_id', $loan->id)
                    ->limit(1)->offset($offset)->get();
                    $interest = ($loan->amount * $loan->interest_percentage/100);
                    $penalty = ($loan->amount * $loan->penalty_rate/100);
                    $curr_date = Carbon\Carbon::now();
                    @endphp
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center">{{$loan->borrower_ref}}</td>
                        <td>
                            <p>Name: <b>{{ $loan->borrow }}</b></p>
                            <p>Plan: <b class="">{{ $loan->plan }}</b></p>
                        </td>
                        <td>
                            <p>Capital: <b>{{ number_format($loan->shared_capital, 2) }}</b></p>
                            <p>Balance: <b>{{ number_format($loan->amount, 2) }}</b></p>
                        </td>
                        <td>
                            @if ($loan->status == 2)
                            @foreach ($next as $nextDate)
                            <small>
                                <p><b>{{ date('M d, Y', strtotime($nextDate->date_due)) }}</b></p>
                            </small>
                            @endforeach
                            @php
                            $withInterest = Carbon\Carbon::parse($nextDate->date_due)->format('d')
                            @endphp
                            @if ($withInterest >= 1 && $withInterest <= 15) <p class="text-primary">Principal:
                                <b>500.00</b></p>
                                @elseif ($withInterest >= 16 && $withInterest <= 31) <p class="text-primary">Principal:
                                    <b>500.00</b></p>
                                    <p class="text-danger">Interest: <b>{{ number_format($interest, 2) }}</b></p>
                                    @endif
                                    @if($curr_date >= $nextDate->date_due)
                                    <p class="text-danger">Penalty: <b>{{ $penalty }}</b></p>
                                    @endif
                                    @else
                                    <p class="text-center">N/A</p>
                                    @endif
                        </td>
                        <td class="text-center">
                            @if ($loan->status == 0)
                            <span class="badge rounded-pill text-bg-warning">For Approval</span>
                            @elseif ($loan->status == 1)
                            <span class="badge rounded-pill text-bg-info">Approved</span>
                            @elseif ($loan->status == 2)
                            <span class="badge rounded-pill text-bg-primary">Active</span>
                            @elseif ($loan->status == 3)
                            <span class="badge rounded-pill text-bg-danger">Denied</span>
                            @elseif ($loan->status == 4)
                            <span class="badge rounded-pill text-bg-success">Complete</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button type="button" value="{{$loan->id}}" id="editApplicant"
                                class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-eye"></i></button>
                            <button type="button" class="btn btn-outline-danger btn-sm"><i
                                    class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="addApplication" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{route('loans.store')}}" id="applicantForm" method="post" class="myForm">
            @csrf
            <input type="hidden" name="id" id="id">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Loan Application Form</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="borrower">Borrower:</label>
                        <select name="borrower_id" id="borrower_id" class="appliSelect2">
                            <option value=""></option>
                            @foreach ($borrowers as $borrow)
                            <option value="{{$borrow->id}}">{{ $borrow->borrower }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="capital">Shared Capital:</label>
                            <input type="text" name="shared_capital" id="shared_cap" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="">Years of Service:</label>
                            <select name="years_service" id="years_serv" class="appliSelect2" readonly>
                                <option value=""></option>
                                <option value="1">1-4 Years</option>
                                <option value="2">5-9 Years</option>
                                <option value="3">10-Up Years</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="form-group col-md-6">
                            <label for="">Loan Plan:</label>
                            <select name="plan_id" id="plan_id"
                                class="appliSelect2 @error('plan_id') is-invalid @enderror">
                                <option value=""></option>
                                @foreach ($plans as $plan)
                                <option value="{{$plan->id}}">{{ $plan->plan_name }} | {{$plan->interest_percentage}}% |
                                    {{ $plan->penalty_rate }}%</option>
                                @endforeach
                            </select>
                            <small>Plan [interest%, Penalty%]</small><br>
                            @error('plan_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="form-group mt-3">
                                <label for="">Amount Borrowed:</label>
                                <input type="number" name="amount" id="amount"
                                    class="form-control @error('amount') is-invalid @enderror">
                            </div>
                            @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="">Purpose:</label>
                            <textarea name="purpose" id="purpose" cols="30" rows="5"
                                class="form-control @error('purpose') is-invalid @enderror"></textarea>
                            @error('purpose')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3 justify-content-center status_radio">
                        <div class="col-sm-3 approved">
                            <div class="form-check radio">
                                <input class="form-check-input" type="radio" value="1" name="status" id="Approved">
                                <label class="form-check-label text-success" for="Approved">Approved</label>
                            </div>
                        </div>
                        <div class="col-sm-3 active">
                            <div class="form-check radio">
                                <input class="form-check-input" type="radio" value="2" name="status" id="Active">
                                <label class="form-check-label text-primary" for="Active">Active</label>
                            </div>
                        </div>
                        <div class="col-sm-3 denied">
                            <div class="form-check radio">
                                <input class="form-check-input" type="radio" value="3" name="status" id="denied">
                                <label class="form-check-label text-danger" for="denied">Denied</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer botton_save">
                    <button type="submit" class="btn btn-success btn-lg px-5">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.myForm').validate({
        rules: {
            borrower_id: {
                required: true,
            },
            amount: {
                required: true,
            },
            amount: {
                required: true,
            },
            purpose: {
                required: true,
            },
            status: {
                required: true,
            },
        },
        messages: {
            borrower_id: {
                required: 'Please Enter First Name',
            },
            amount: {
                required: 'Please Enter Amount',
            },
            amount: {
                required: 'Please Enter Amount',
            },
            purpose: {
                required: 'Please Enter Purpose',
            },
            status: {
                required: 'Please Select Status',
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
</script>
<script>
$(document).on('click', '#clear', function() {
    $("#applicantForm")[0].reset();
    $("#id").val("");
    $("#borrower_id").val("").trigger("change");
    $("#plan_id").val("").trigger("change");
    $("#years_serv").val("").trigger("change");
    $(".status_radio").css("display", "none");
    $(':input[type="submit"]').prop('disabled', false);
    $("#applicantForm").attr("action", "{{ route('loans.store') }}");
})
$(document).ready(function() {
    $("#borrower_id").change(function() {
        var borrow_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/admin/loan-lists/getData/" + borrow_id,
            success: function(res) {
                $("#shared_cap").val(res.borrow.shared_capital);
                $("#years_serv").val(res.borrow.years_service).trigger("change");
            }
        })
    });

    $(document).on('click', '#editApplicant', function() {
        var loan_id = $(this).val();

        $("#addApplication").modal('show');

        $.ajax({
            type: "GET",
            url: "/admin/loan-lists/applicant/edit/" + loan_id,
            success: function(res) {
                $("#borrower_id").val(res.appli.borrower_id).trigger("change");
                $("#plan_id").val(res.appli.plan_id).trigger("change");
                $("#amount").val(res.appli.amount);
                $("#purpose").val(res.appli.purpose);
                $("#id").val(loan_id);
                $("#applicantForm").attr("action", "{{ route('loans.update') }}");

                var status = res.appli.status;
                if (status === 0) {
                    $(".active").css("display", "none");
                } else if (status === 2) {
                    $(".status_radio").css("display", "none");
                    $(':input[type="submit"]').prop('disabled', true);
                } else {
                    $(".status_radio").css({
                        "display": "flex",
                        "justify-content": "center",
                    });
                    $(':input[type="submit"]').prop('disabled', false);
                    $(".active").css({
                        "display": "flex",
                        "justify-content": "center",
                    });
                }
            }
        })
    })
});
</script>
@endsection