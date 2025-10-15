@extends('admin.body.header')
@section('admin')

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h4>New/Current Borrower Lists</h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label for="">Office/Departmnet</label>
                    <select name="" id="fDept" class="form-select select2">
                        <option value=""></option>
                        @foreach ($depts as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->dept_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-4">
                    <label for="">Status</label>
                    <select name="" id="fStatus" class="form-select select2">
                        <option value=""></option>
                        <option value="0">New Applicant</option>
                        <option value="1">Existing Member</option>
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered" id="filterTable">
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
                        <td class="text-center">{{ $i++ }}</td>
                        <td class="text-center">{{ $data->borrower_ref }}</td>
                        <td>{{ $data->borrower }}</td>
                        <td class="text-center">{{ number_format($data->shared_capital, 2) }}</td>
                        <td class="text-center">{{ $data->dept }}</td>
                        <td class="text-center">
                            @if($data->status == 0)
                            <span class="badge rounded-pill text-bg-secondary">New Applicant</span>
                            @elseif($data->status == 1)
                            <span class="badge rounded-pill text-bg-primary">Existing Member</span>
                            @else
                            @endif
                        </td>
                        <td class="text-center">{{ date('M d, Y', strtotime($data->created_at)) }}</td>
                        <td class="text-center">
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
                $("#dept_id").val(res.borrower.dept_id);
                $("#status").val(res.borrower.status);
                $("#id").val(borrower_id);
            }
        });
    });
});
</script>

<div class="modal fade" id="editBorrower" tabindex="-1" aria-labelledby="editBorrowerLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('borrow.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="id">

                <div class="modal-header">
                    <h4 class="modal-title" id="editBorrowerLabel">
                        <i class="fa fa-user-edit me-2"></i> Edit Borrower
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body py-4">
                    <!-- Basic Info -->
                    <h5 class="text-primary mb-3 border-bottom pb-2">
                        <i class="fa fa-id-card me-2"></i> Basic Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="emp_id" class="form-label">Employee ID</label>
                            <input type="text" name="emp_id" id="emp_id" class="form-control" placeholder="EMP12345">
                        </div>

                        <div class="col-md-2">
                            <label for="shared_capital" class="form-label">Shared Capital</label>
                            <input type="number" name="shared_capital" id="shared_capital" class="form-control"
                                placeholder="e.g. 5000">
                        </div>

                        <div class="col-md-4">
                            <label for="dept_id" class="form-label">Department</label>
                            <select name="dept_id" id="dept_id" class="form-select editBorrower">
                                <option value="" selected disabled>Select Department</option>
                                @foreach ($depts as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->dept_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="" selected disabled>Select Status</option>
                                <option value="0">New Applicant</option>
                                <option value="1">Existing Member</option>
                            </select>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <h5 class="text-primary mt-5 mb-3 border-bottom pb-2">
                        <i class="fa fa-user me-2"></i> Personal Information
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Juan">
                        </div>

                        <div class="col-md-4">
                            <label for="middlename" class="form-label">Middle Name</label>
                            <input type="text" name="middlename" id="middlename" class="form-control"
                                placeholder="Santos">
                        </div>

                        <div class="col-md-4">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control"
                                placeholder="Dela Cruz">
                        </div>

                        <div class="col-md-4">
                            <label for="date_birth" class="form-label">Date of Birth</label>
                            <input type="date" name="date_birth" id="date_birth" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="contact_no" class="form-label">Contact Number</label>
                            <input type="text" name="contact_no" id="contact_no" class="form-control"
                                placeholder="09XXXXXXXXX">
                        </div>

                        <div class="col-md-4">
                            <label for="years_service" class="form-label">Years of Service</label>
                            <select name="years_service" id="years_service" class="form-select">
                                <option value="" selected disabled>Select Duration</option>
                                <option value="1">1–4 Years</option>
                                <option value="2">5–9 Years</option>
                                <option value="3">10+ Years</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address -->
                    <h5 class="text-primary mt-5 mb-3 border-bottom pb-2">
                        <i class="fa fa-map-marker-alt me-2"></i> Address
                    </h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <textarea name="address" id="address" rows="4" class="form-control"
                                placeholder="Enter complete address..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fa fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection