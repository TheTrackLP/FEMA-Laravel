@extends('admin.body.header')
@section('admin')

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <h4>New/Current Borrower Lists</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="">Office/Departmnet</label>
                    <select name="" id="" class="form-select">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="">Status</label>
                    <select name="" id="" class="form-select">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <hr>
            <table class="table table-bordered" id="borrowerTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">CV#</th>
                        <th class="text-center">Borrower's Name</th>
                        <th class="text-center">Shared Capital</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Date Joined</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection