@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
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
            <table class="table table-bordered" id="borrowerTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Reference and CV#</th>
                        <th class="text-center">Borrower's Details</th>
                        <th class="text-center">Shared Capital</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Penalty</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection