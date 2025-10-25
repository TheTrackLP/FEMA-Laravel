@extends('admin.body.header')
@section('admin')

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h4>New Applicant/Existing Borrower Lists</h4>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-4">
                    <label for="">Office/Departmnet</label>
                    <select name="" id="borrowerDept" class="form-select select2">
                        <option value=""></option>
                        @foreach ($depts as $dept)
                        <option value="{{ $dept->dept_name }}">{{ $dept->dept_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4">
                    <label for="">Status</label>
                    <select name="" id="borrowerStatus" class="form-select select2">
                        <option value=""></option>
                        <option value="New Applicant">New Applicant</option>
                        <option value="Existing Member">Existing Member</option>
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered" id="borrowersTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Reference #</th>
                        <th class="text-center">Borrower's Name</th>
                        <th class="text-center">Shared Capital</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date Joined</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowers as $data)
                    @php
                    $i = 1;
                    @endphp
                    <tr>
                        <td class="text-center align-middle">{{ $i++ }}</td>
                        <td class="text-center align-middle">{{ $data->borrower_ref }}</td>
                        <td class="align-middle">{{ $data->borrower }}</td>
                        <td class="text-center align-middle">{{ number_format($data->shared_capital, 2) }}</td>
                        <td class="text-center align-middle">{{ $data->dept }}</td>
                        <td class="text-center align-middle">
                            @if($data->status == 0)
                            <span class="badge rounded-pill text-bg-secondary">New Applicant</span>
                            @elseif($data->status == 1)
                            <span class="badge rounded-pill text-bg-primary">Existing Member</span>
                            @else
                            @endif
                        </td>
                        <td class="text-center align-middle">{{ date('M d, Y', strtotime($data->created_at)) }}</td>
                        <td class="text-center align-middle">
                            <button type="button" class="btn btn-warning btn-sm" value="{{$data->id}}" id="EditB"><i
                                    class="fa-solid fa-pen-to-square"></i></button>
                            <a href="{{route('borrow.delete', $data->id)}}" id="delete" class="btn btn-danger btn-sm"><i
                                    class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="editBorrower" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('borrow.update')}}" method="post" class="myForm">
                @csrf
                <div class="modal-header">
                    <h3>Edit Borrower</h3>
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body my-4">
                    <div class="row mb-4">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group col-md-2">
                            <label for="">Employee ID</label>
                            <input type="text" name="emp_id" id="emp_id" class="form-control">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">Shared Capital</label>
                            <input type="number" name="shared_capital" id="shared_capital" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Department</label>
                            <select name="dept_id" id="dept_id" class="editSelect2">
                                <option value=""></option>
                                @foreach ($depts as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->dept_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Status</label>
                            <select name="status" id="status" class="appliSelect2">
                                <option value="0">New Applicant</option>
                                <option value="1">Existing Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="form-group col-md-4 mb-4">
                            <label for="">First Name</label>
                            <input type="text" name="firstname" id="firstname" class="form-control">
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="">Middle Name</label>
                            <input type="text" name="middlename" id="middlename" class="form-control">
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control">
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="">Date of Birth</label>
                            <input type="date" name="date_birth" id="date_birth" class="form-control">
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="">Contact No.</label>
                            <input type="text" name="contact_no" id="contact_no" class="form-control">
                        </div>
                        <div class="form-group col-md-4 mb-4">
                            <label for="">Years of Service</label>
                            <select name="years_service" id="years_service" class="form-select">
                                <option value="1">1-4 Years</option>
                                <option value="2">5-9 Years</option>
                                <option value="3">10-Up Years</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-lg float-end px-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('.myForm').validate({
        ignore: [],
        rules: {
            emp_id: {
                required: true,
            },
            shared_capital: {
                required: true,
            },
            dept_id: {
                required: true,
            },
            status: {
                required: true,
            },
            firstname: {
                required: true,
            },
            middlename: {
                required: true,
            },
            lastname: {
                required: true,
            },
            date_birth: {
                required: true,
            },
            contact_no: {
                required: true,
            },
            years_service: {
                required: true,
            },
            address: {
                required: true,
            },
        },
        messages: {
            emp_id: {
                required: 'Please Enter Employee ID',
            },
            shared_capital: {
                required: 'Please Enter Shared Capital',
            },
            dept_id: {
                required: 'Please Select Department',
            },
            status: {
                required: 'Please Select Status',
            },
            firstname: {
                required: 'Please Enter First Name',
            },
            middlename: {
                required: 'Please Enter Middle Name',
            },
            lastname: {
                required: 'Please Enter Last Name',
            },
            date_birth: {
                required: 'Please Enter Date of Birth',
            },
            contact_no: {
                required: 'Please Enter Contact No.',
            },
            years_service: {
                required: 'Please Select Years of Serivce',
            },
            address: {
                required: 'Please Enter Address',
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
$(document).ready(function() {
    $(document).on('click', '#EditB', function() {
        var borrower_id = $(this).val();

        $("#editBorrower").modal("show");


        $.ajax({
            type: "GET",
            url: "/admin/borrower/show/" + borrower_id,
            success: function(res) {
                $("#firstname").val(res.borrower.firstname);
                $("#middlename").val(res.borrower.middlename);
                $("#lastname").val(res.borrower.lastname);
                $("#date_birth").val(res.borrower.date_birth);
                $("#contact_no").val(res.borrower.contact_no);
                $("#years_service").val(res.borrower.years_service);
                $("#address").val(res.borrower.address);
                $("#emp_id").val(res.borrower.emp_id);
                $("#shared_capital").val(res.borrower.shared_capital);
                $("#dept_id").val(res.borrower.dept_id).trigger("change");
                $("#status").val(res.borrower.status);
                $("#id").val(borrower_id);
            }
        });
    });
});
</script>


@endsection