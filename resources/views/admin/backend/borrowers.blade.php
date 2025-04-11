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
                        <option value="0">New Member</option>
                        <option value="1">Exist Member</option>
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
                            <span class="badge rounded-pill text-bg-secondary">New</span>
                            @elseif($data->status  == 1)
                            <span class="badge rounded-pill text-bg-primary">Exist</span>
                            @else
                            @endif
                        </td>
                        <td class="text-center">{{ date('M d, Y', strtotime($data->created_at)) }}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-sm" value="{{$data->id}}" id="EditB"><i class="fa-solid fa-pen-to-square"></i></button>             
                            <a href="{{route('borrow.delete', $data->id)}}" id="delete" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(document).on('click', '#EditB', function(){
            var borrower_id = $(this).val();

            $("#editBorrower").modal("show");

            $.ajax({
                type: "GET",
                url: "/admin/borrower/show/" + borrower_id,
                success: function(res){
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

<div class="modal fade" id="editBorrower">
    <div class="modal-dialog modal-xl modal-dialog-centered" style="max-len">
        <div class="modal-content">
            <form action="{{route('borrow.update')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h3>Edit Borrower</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body my-5">
                    <div class="row">
                        <div class="col-lg-2 mb-4">
                            <input type="hidden" name="id" id="id">
                            <label for="">Employee ID</label>
                            <input type="text" name="emp_id" id="emp_id" class="form-control">
                        </div>
                        <div class="col-lg-2 mb-4">
                            <label for="">Shared Capital</label>
                            <input type="text" name="shared_capital" id="shared_capital" class="form-control">
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label for="">Department</label>
                            <select name="dept_id" id="dept_id" class="form-select editBorrower">
                                <option value=""></option>
                                <option value="0">Pending</option>
                                <option value="1">Member</option>
                            </select>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label for="">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value=""></option>
                                <option value="0">Pending</option>
                                <option value="1">Member</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-4 mb-4">
                                    <label for="">First Name</label>
                                    <input type="text" name="firstname" id="firstname" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label for="">Middle Name</label>
                                    <input type="text" name="middlename" id="middlename" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label for="">Last Name</label>
                                    <input type="text" name="lastname" id="lastname" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label for="">Date of Birth</label>
                                    <input type="date" name="date_birth" id="date_birth" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label for="">Contact No:</label>
                                    <input type="text" name="contact_no" id="contact_no" class="form-control">
                                </div>
                                <div class="col-lg-4 mb-4">
                                    <label for="">Year of Service</label>
                                    <select name="years_service" id="years_service" class="form-select">
                                        <option value="" selected disable>Select Here:</option>
                                        <option value="1">1-4 Years</option>
                                        <option value="2">5-9 Years</option>
                                        <option value="3">10-Up Years</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="row">
                                <div class="col">
                                    <label for="">Address</label>
                                    <textarea name="address" id="address" rows="7" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-lg float-end px-3">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection