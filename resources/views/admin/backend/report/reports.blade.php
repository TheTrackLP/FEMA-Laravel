@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3>Reports</h3>
        </div>
        <div class="card-body">
            <div class="col-md-4">
                <label for="">Month/Year</label>
                <input type="month" name="" id="" class="form-control">
            </div>
            <hr>
            <table class="table table-bordered" id="reportTable">
                <thead class="table-info">
                    <tr>
                        <th class="text-center" rowspan="2">#</th>
                        <th class="text-center" rowspan="2">Month Year</th>
                        <th class="text-center">Plan</th>
                        <th class="text-center" role="2">Paid-In</th>
                        <th class="text-center" role="2">Other Receivable</th>
                        <th class="text-center" role="2">Total</th>
                    </tr>
                    <tr>
                        <th class="text-center">PRIN</th>
                        <th class="text-center">INT</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@endsection