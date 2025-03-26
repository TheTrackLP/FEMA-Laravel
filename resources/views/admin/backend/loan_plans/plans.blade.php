@extends('admin.body.header')
@section('admin')

<div class="container-fluid">
    <div class="mt-3"></div>
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary btn-lg float-end" data-bs-toggle="modal"
                data-bs-target="#addPlan"><i class="fa-solid fa-plus"></i> Add
                Plan</button>
            <h3 class="card-title">
                Loan Plans
            </h3>
        </div>
    </div>
    <hr>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col-md-3">
            <div class="card border-dark mb-3">
                <div class="card-header">
                    <h4 class="card-title">
                        <label for="">Plan</label>
                   </h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Interest:</strong>
                            <span class="float-end text-primary fw-bold">Inte%</span>
                        </li>
                        <li class="mb-2">
                            <strong>Penalty:</strong>
                            <span class="float-end text-danger fw-bold">Penal%</span>
                        </li>
                    </ul>
                    <p class="card-text">Desc.</p>
                </div>
                <div class="card-footer text-center">
                    <button type="button" class="btn btn-success btn-sm px-4 col-xs-3">View
                    </button>
                    <button type="button" class="btn btn-warning btn-sm px-4 col-xs-3" id="editData"
                        value="">Edit
                    </button>
                    <a href="" class="btn btn-danger btn-sm px-4 col-xs-3"
                        id="delete">Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection