@extends('admin.body.header')
@section('admin')
@php
$i = 1;
$y = 1;
@endphp
<div class="container-fluid">
    <div class="mt-3"></div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Staff Accounts</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name/Username</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($staff_accts as $staff)
                            <tr>
                                <td class="text-center">{{ $i++ }}</td>
                                <td>
                                    <p>Name: <b>{{ $staff->username }}</b></p>
                                    <p>Username: <b>{{ $staff->username }}</b></p>
                                </td>
                                <td class="text-center">{{ $staff->roles }}</td>
                                <td class="text-center">
                                    @if ($staff->status == 1)
                                    <span class="badge text-bg-primary">Active</span>
                                    @elseif ($staff->status == 0)
                                    <span class="badge text-bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-sm acctMod" value="{{$staff->id}}"><i
                                            class="fa-solid fa-user-pen"></i></button>
                                    <button class="btn btn-outline-danger btn-sm"><i
                                            class="fa-solid fa-trash-can"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Borrowers Accounts</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name/Username</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrow_accts as $borrow)
                            <tr>
                                <td class="text-center">{{ $y++ }}</td>
                                <td>
                                    <p>Name: <b>{{ $borrow->borrower }}</b></p>
                                    <p>Username: <b>{{ $borrow->username }}</b></p>
                                </td>
                                <td class="text-center">{{ $borrow->roles }}</td>
                                <td class="text-center">
                                    @if ($borrow->status == 1)
                                    <span class="badge text-bg-primary">Active</span>
                                    @elseif ($borrow->status == 0)
                                    <span class="badge text-bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-sm acctMod" value="{{ $borrow->id }}"><i
                                            class="fa-solid fa-user-pen"></i></button>
                                    <button class="btn btn-outline-danger btn-sm"><i
                                            class="fa-solid fa-trash-can"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="acctModal" tabindex="0">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('accts.update')}}" method="post">
                @csrf
                <div class="modal-header">
                    <h3>Account Form</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col form-group">
                            <input type="hidden" name="id" id="id">
                            <label for="">Borrower's Name:</label>
                            <input type="text" name="borrower_id" id="acct_name" class="form-control" readonly>
                        </div>
                        <div class="col form-group">
                            <label for="">Username:</label>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col form-group">
                            <label for="">Email:</label>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label for="">Status:</label>
                            <select name="status" id="acct_stat" class="form-select">
                                <option value="" selected disabled> Select an option:</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col form-group">
                            <label for="">New Password:</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col form-group">
                            <label for="">Confirm Password:</label>
                            <input type="password" name="confirm_password" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success px-5">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '.acctMod', function() {
        var acct_id = $(this).val();

        $("#acctModal").modal("show");

        $.ajax({
            type: "GET",
            url: "/admin/accounts/info/" + acct_id,
            success: function(res) {
                var borrowID = res.acct.borrower_id;
                if (borrowID == 0) {
                    $("#acct_name").val(res.acct.username);
                } else {
                    $("#acct_name").val(res.acct.acct_name);
                }
                $("#username").val(res.acct.username);
                $("#acct_stat").val(res.acct.status);
                $("#email").val(res.acct.email);
                $("#id").val(acct_id);
            }
        })
    })
});
</script>
@endsection