@extends('admin.body.header')
@section('admin')
<style>
.card:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
}

</style>
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <hr class="py-3">
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-warning shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-dark small mb-1">For Approval Loans</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $for_approval }}</div>
                            </div>
                            <i class="fa fa-hourglass-start fa-2x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-primary text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small mb-1">Approved Requests</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $approved }}</div>
                            </div>
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-info text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small mb-1">Active Loans</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $active }}</div>
                            </div>
                            <i class="fa fa-play-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card bg-danger text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small mb-1">Denied Loans</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $denied }}</div>
                            </div>
                            <i class="fa fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-success text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-white-75 small mb-1">Completed Loans</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $complete }}</div>
                            </div>
                            <i class="fa fa-check-double fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div> 
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-primary text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-3">
                                <div class="text-white-75 small">Pending Borrowers</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $pending }}</div>
                            </div>
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card bg-primary text-white shadow h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mr-3">
                                <div class="text-white-75 small">Member Borrowers</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $curr_members }}</div>
                            </div>
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection