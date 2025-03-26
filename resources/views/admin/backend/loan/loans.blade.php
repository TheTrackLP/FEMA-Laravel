@extends('admin.body.header')
@section('admin')

<div class="container-fluid mt-3">
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-end"><i class="fa-solid fa-plus"></i> Create New Application</button>
            <h3>Loan Lists</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="">Type of Loan</label>
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
                        <th class="text-center">Years of Service</th>
                        <th class="text-center">Plan</th>
                        <th class="text-center">Next Payment Details</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection