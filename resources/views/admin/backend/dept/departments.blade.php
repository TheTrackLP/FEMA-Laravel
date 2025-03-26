@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Add Department
                </div>
                <div class="card-body">
                    <label for="">Department Name</label>
                    <input type="text" name="" id="" class="form-control">
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-end">Add Department</button>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Departments
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-info">
                            <tr>
                                <th>#</th>
                                <th>Departments</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection