@php
    $i = 1;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Dashboard</title>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<style>
    body {
        background-image: linear-gradient(to bottom right, #00AFB9, #FED9B7);
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    td,p{
        margin: 0;
    }
    label{
            font-weight: bold;
        }
    .radio{
        font-size: 23px;
    }
</style>
<body>
    <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div class="col-md-3 d-flex flex-column align-items-center text-center">
                <div class="d-flex flex-column align-items-center text-center p-5 py-10">
                    <h6 class="mb-2">{{ $profileData->borrower }}</h6>
                    <p class="text-muted mb-2">Username: {{ $profileData->username }}</p>
                    <p class="text-muted mb-2">{{ $profileData->email }}</p>
                    <p class="mb-2">Shared Capital: <strong>{{ number_format($profileData->shared_capital, 2) }}</strong></p>
                    <hr class="w-100">
                    <p>
                        @if ($profileData->status == 0)
                        <h3><span class="badge text-bg-secondary">New</span></h3>
                        @elseif ($profileData->status == 1)
                        <h3><span class="badge text-bg-primary">Member</span></h3>
                        @endif
                    </p>
                    <hr class="w-100">
                    <a href="{{route('borrow.logout')}}" class="btn btn-danger"><i class="fa-solid fa-power-off"></i> Logout</a>
                </div>
            </div>
            <div class="col-md-9 border-right">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="text-right">My Dashboard</h3>
                        @if ($profileData->status != 1)       
                        @else
                        <button type="button" class="btn btn-primary px-4 apply" data-bs-toggle="modal" data-bs-target="#applyLoan"><i class="fa-solid fa-plus"></i> Apply New Loan</button>
                        @endif
                    </div>
                    <hr>
                    <table class="table table-bordered" id="borrowerTable">
                        <thead class="table-info">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Type of Loan</th>
                                <th class="text-center">Next Payment Details</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                <td class="text-center">{{ $i++ }}</td>
                                <td>
                                    <p>Plan: <b>{{ $loan->plan }}</b></p>
                                    <p>Balance: <b>{{ number_format($loan->amount, 2) }}</b></p>
                                </td>
                                <td>
                                    @if ($loan->status == 2)
                                    @foreach ($next as $nextDate)
                                        <small><p><b>{{ date('M d, Y', strtotime($nextDate->date_due)) }}</b></p></small>
                                    @endforeach
                                    @php
                                    $withInterest = Carbon\Carbon::parse($nextDate->date_due)->format('d')
                                    @endphp
                                    @if ($withInterest >= 1 && $withInterest <= 15)
                                    <p class="text-primary">Principal: <b>500.00</b></p>
                                    @elseif ($withInterest >= 6 && $withInterest <= 31)
                                    <p class="text-primary">Principal: <b>500.00</b></p>
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
                                    <p>{{ date("M d, Y", strtotime($loan->created_at)) }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="applyLoan" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form action="{{route('borrow.apply')}}" id="applicantForm" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Loan Application Form</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="borrower">Borrower:</label>
                            <input type="text" value="{{ $profileData->borrower }}" class="form-control" readonly>
                            <input type="hidden" name="borrower_id" id="borrower_id" value="{{ $profileData->borrower_id }}">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="capital">Shared Capital:</label>
                                <input type="text" name="shared_capital" id="shared_cap" value="{{ $profileData->shared_capital }}" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="">Years of Service:</label>
                                <select name="years_service" id="years_serv" class="selectBorrow">
                                    <option value=""></option>
                                    <option value="1" {{ $profileData->years_service == "1" ? 'selected' : "" }}>1-4 Years</option>
                                    <option value="2" {{ $profileData->years_service == "2" ? 'selected' : "" }}>5-9 Years</option>
                                    <option value="3" {{ $profileData->years_service == "3" ? 'selected' : "" }}>10-Up Years</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <label for="">Loan Plan:</label>
                                <select name="plan_id" id="plan_id" class="selectBorrow">
                                    <option value=""></option>
                                    @foreach ($plans as $plan)
                                    <option value="{{$plan->id}}">{{ $plan->plan_name }} | {{$plan->interest_percentage}}% | {{ $plan->penalty_rate }}%</option>
                                    @endforeach
                                </select>
                                <small>Plan [interest%, Penalty%]</small><br>
                                <div class="form-group mt-3">
                                    <label for="">Amount Borrowed:</label>
                                    <input type="number" name="amount" id="amount" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="">Purpose:</label>
                                <textarea name="purpose" id="purpose" cols="30" rows="5" class="form-control"></textarea>
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

    <script>
        $(document).ready(function(){
            $('.selectBorrow').select2({
                width: "100%",
                placeholder: "Select an option",
                dropdownParent: $("#applyLoan"),
            })
        });
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type','info') }}"
        switch(type){
           case 'info':
           toastr.info(" {{ Session::get('message') }} ");
           break;

           case 'success':
           toastr.success(" {{ Session::get('message') }} ");
           break;

           case 'warning':
           toastr.warning(" {{ Session::get('message') }} ");
           break;

           case 'error':
           toastr.error(" {{ Session::get('message') }} ");
           break; 
        }
        @endif
    </script>
</body>
</html>