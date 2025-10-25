@extends('admin.body.header')
@section('admin')
<style>
.card:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}
</style>
<main>

    <div class="container-fluid py-4">
        <h1 class="mt-4">Dashboard {{ $curr_date }}</h1>
        <hr class="py-3">
        <div class="row g-3">
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-primary text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">For Approval Loans</h6>
                            <h3 class="fw-bold mb-0">{{ $for_approval }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-success text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Approved Requests</h6>
                            <h3 class="fw-bold mb-0">{{ $approved }}</h3>
                        </div>
                        <i class="fas fa-thumbs-up fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-info text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Active Loans</h6>
                            <h3 class="fw-bold mb-0">{{ $active }}</h3>
                        </div>
                        <i class="fas fa-hand-holding-usd fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-danger text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Denied Loans</h6>
                            <h3 class="fw-bold mb-0">{{ $denied }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-success text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Completed Loans</h6>
                            <h3 class="fw-bold mb-0">{{ $complete }}</h3>
                        </div>
                        <i class="fas fa-clipboard-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-warning text-dark py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Pending Borrowers</h6>
                            <h3 class="fw-bold mb-0">{{ $pending }}</h3>
                        </div>
                        <i class="fas fa-user-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-dark text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Member Borrowers</h6>
                            <h3 class="fw-bold mb-0">{{ $curr_members }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card shadow-sm border-0 rounded-4 bg-danger text-white py-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase fw-bold mb-2">Overdue Loans</h6>
                            <h3 class="fw-bold mb-0">{{ $overdue }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection