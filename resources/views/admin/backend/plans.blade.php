@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="mt-3"></div>
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary btn-lg float-end px-5" id="clearFrom" data-bs-toggle="modal"
                data-bs-target="#addPlan"><i class="fa-solid fa-plus"></i> Add
                Plan</button>
            <h3 class="card-title">
                Loan Plans
            </h3>
        </div>
    </div>
    <hr>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($plans as $plan)
        <div class="col-md-3">
            <div class="card border-dark mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <label for="" class="uppercase">{{$plan->plan_name}}</label>
                    </h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Interest:</strong>
                            <span class="float-end text-primary fw-bold">{{$plan->interest_percentage}}%</span>
                        </li>
                        <li class="mb-2">
                            <strong>Penalty:</strong>
                            <span class="float-end text-danger fw-bold">{{$plan->penalty_rate}}%</span>
                        </li>
                    </ul>
                    <p class="card-text">{{ $plan->plan_desc }}</p>
                </div>
                <div class="card-footer text-center">
                    <button type="button" class="btn btn-primary px-4 col-xs-3"><i class="fa-solid fa-eye"></i>
                    </button>
                    <button type="button" value="{{$plan->id}}" class="btn btn-warning px-4 col-xs-3" id="editPlan"
                        value=""><i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <a href="{{ route('plan.delete', $plan->id) }}" class="btn btn-danger px-4 col-xs-3" id="delete"><i
                            class="fa-solid fa-trash-can"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="addPlan" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('plan.add') }}" id="planForm" method="post" class="myForm">
            <input type="hidden" name="id" id="id">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Loan Plan Forms</h3>
                    <button type="button" class="btn-close" id="formPlan" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="plan_name">Plan Name:</label>
                        <input type="text" name="plan_name" id="plan_name"
                            class="form-control @error('plan_name') is-invalid @enderror ">
                        @error('plan_name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="row mb-3">
                        <div class="form-group col-sm-6">
                            <label for="">Interest Percentage:</label>
                            <div class="input-group mb-3">
                                <input type="number" name="interest_percentage" id="interest_percentage" step="any"
                                    class="form-control @error('interest_percentage') is-invalid @enderror ">
                                <span class="input-group-text">%</span>
                                @error('interest_percentage')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">Pernalty Percentage:</label>
                            <div class="input-group mb-3">
                                <input type="number" name="penalty_rate" id="penalty_rate" step="any"
                                    class="form-control @error('penalty_rate') is-invalid @enderror">
                                <span class="input-group-text">%</span>
                                @error('penalty_rate')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Description</label>
                        <textarea name="plan_desc" id="plan_desc" cols="30" rows="10"
                            class="form-control @error('plan_desc') is-invalid @enderror"></textarea>
                        @error('plan_desc')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-5">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.myForm').validate({
        rules: {
            plan_name: {
                required: true,
            },
            interest_percentage: {
                required: true,
            },
            penalty_rate: {
                required: true,
            },
            plan_desc: {
                required: true,
            },
        },
        messages: {
            plan_name: {
                required: 'Please Enter Plan Name',
            },
            interest_percentage: {
                required: 'Please Enter Interest Percentage',
            },
            penalty_rate: {
                required: 'Please Enter Penalty Rate',
            },
            plan_desc: {
                required: 'Please Enter Description',
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
$(document).on('click', '#clearFrom', function() {
    $("#planForm")[0].reset(); // Reset form fields
    $("#id").val(""); // Clear hidden input for ID
    $("#planForm").attr("action", "{{ route('plan.add') }}"); // Ensure it's for adding
})
$(document).ready(function() {

    $(document).on('click', '#editPlan', function() {
        var plan_id = $(this).val();

        $("#addPlan").modal("show");

        $.ajax({
            type: "GET",
            url: "/admin/loan-plans/edit/" + plan_id,
            success: function(res) {
                $("#plan_name").val(res.plan.plan_name);
                $("#interest_percentage").val(res.plan.interest_percentage);
                $("#penalty_rate").val(res.plan.penalty_rate);
                $("#plan_desc").val(res.plan.plan_desc);
                $("#id").val(plan_id);
                $("#planForm").attr("action", "{{ route('plan.update') }}");
            }
        })
    });
});
</script>
@endsection