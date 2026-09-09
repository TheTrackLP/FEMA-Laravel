<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<template>
    <AdminLayout>
        <div>
            <div class="mb-4">
                <h2 class="h4 mb-1">Dashboard</h2>
                <p class="text-muted mb-0 small">
                    Overview of FEMA loan activities and financial records.
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-4">
                <button class="btn btn-primary btn-quick">
                    <i class="bi bi-plus-lg me-1"></i>New Loan Application
                </button>
                <button class="btn btn-outline-secondary btn-quick">
                    <i class="bi bi-person-plus me-1"></i>Add Borrower
                </button>
                <button class="btn btn-outline-secondary btn-quick">
                    <i class="bi bi-cash-coin me-1"></i>Record Payment
                </button>
                <button class="btn btn-outline-secondary btn-quick">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>Generate
                    Report
                </button>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="card summary-card shadow-sm h-100">
                        <div
                            class="card-body d-flex justify-content-between align-items-start"
                        >
                            <div>
                                <div class="text-muted small">
                                    Total Borrowers
                                </div>
                                <div class="h4 mb-0 mt-1">1,248</div>
                                <div class="text-muted small mt-1">
                                    Registered borrowers
                                </div>
                            </div>
                            <div
                                class="icon-box bg-primary bg-opacity-10 text-primary"
                            >
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card summary-card shadow-sm h-100">
                        <div
                            class="card-body d-flex justify-content-between align-items-start"
                        >
                            <div>
                                <div class="text-muted small">
                                    Pending Applications
                                </div>
                                <div class="h4 mb-0 mt-1">24</div>
                                <div class="text-warning small mt-1">
                                    Requires attention
                                </div>
                            </div>
                            <div
                                class="icon-box bg-warning bg-opacity-25 text-warning"
                            >
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card summary-card shadow-sm h-100">
                        <div
                            class="card-body d-flex justify-content-between align-items-start"
                        >
                            <div>
                                <div class="text-muted small">Active Loans</div>
                                <div class="h4 mb-0 mt-1">386</div>
                                <div class="text-muted small mt-1">
                                    Currently active
                                </div>
                            </div>
                            <div
                                class="icon-box bg-primary bg-opacity-10 text-primary"
                            >
                                <i class="bi bi-cash-stack"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card summary-card shadow-sm h-100">
                        <div
                            class="card-body d-flex justify-content-between align-items-start"
                        >
                            <div>
                                <div class="text-muted small">
                                    Outstanding Balance
                                </div>
                                <div class="h4 mb-0 mt-1">₱4,285,600.00</div>
                                <div class="text-muted small mt-1">
                                    Total remaining balance
                                </div>
                            </div>
                            <div
                                class="icon-box bg-danger bg-opacity-10 text-danger"
                            >
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <span class="fw-semibold small"
                        >Recent Loan Applications</span
                    >
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Loan Type</th>
                                <th class="text-end">Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-medium">John Dela Cruz</td>
                                <td>Emergency Loan</td>
                                <td class="text-end">₱25,000</td>
                                <td class="text-muted">Sep 9, 2026</td>
                                <td>
                                    <span class="badge bg-warning text-dark"
                                        >Pending</span
                                    >
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-outline-primary btn-sm"
                                        >View</a
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Maria Santos</td>
                                <td>Regular Loan</td>
                                <td class="text-end">₱50,000</td>
                                <td class="text-muted">Sep 8, 2026</td>
                                <td>
                                    <span class="badge bg-success"
                                        >Approved</span
                                    >
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-outline-primary btn-sm"
                                        >View</a
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Ana Reyes</td>
                                <td>Emergency Loan</td>
                                <td class="text-end">₱10,000</td>
                                <td class="text-muted">Sep 8, 2026</td>
                                <td>
                                    <span class="badge bg-info"
                                        >Under Review</span
                                    >
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-outline-primary btn-sm"
                                        >View</a
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Mark Villanueva</td>
                                <td>Tuition Loan</td>
                                <td class="text-end">₱40,000</td>
                                <td class="text-muted">Sep 7, 2026</td>
                                <td>
                                    <span class="badge bg-primary">Active</span>
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-outline-primary btn-sm"
                                        >View</a
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Liza Fernandez</td>
                                <td>Regular Loan</td>
                                <td class="text-end">₱15,000</td>
                                <td class="text-muted">Sep 6, 2026</td>
                                <td>
                                    <span class="badge bg-danger"
                                        >Rejected</span
                                    >
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-outline-primary btn-sm"
                                        >View</a
                                    >
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Grace Lim</td>
                                <td>Tuition Loan</td>
                                <td class="text-end">₱30,000</td>
                                <td class="text-muted">Sep 3, 2026</td>
                                <td>
                                    <span class="badge bg-secondary"
                                        >Completed</span
                                    >
                                </td>
                                <td class="text-end">
                                    <a
                                        href="#"
                                        class="btn btn-outline-primary btn-sm"
                                        >View</a
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="#" class="small fw-medium text-decoration-none"
                        >View All Applications</a
                    >
                </div>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <span class="fw-semibold small">Upcoming Payments</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Borrower</th>
                                <th>Loan No.</th>
                                <th>Due Date</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-medium">John Dela Cruz</td>
                                <td>LN-0231</td>
                                <td class="text-muted">Sep 12, 2026</td>
                                <td class="text-end">₱2,500</td>
                                <td>
                                    <span class="badge bg-info">Upcoming</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Ana Reyes</td>
                                <td>LN-0198</td>
                                <td class="text-muted">Sep 10, 2026</td>
                                <td class="text-end">₱1,200</td>
                                <td>
                                    <span class="badge bg-danger">Overdue</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Mark Villanueva</td>
                                <td>LN-0177</td>
                                <td class="text-muted">Sep 9, 2026</td>
                                <td class="text-end">₱3,000</td>
                                <td>
                                    <span class="badge bg-success">Paid</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Liza Fernandez</td>
                                <td>LN-0142</td>
                                <td class="text-muted">Sep 15, 2026</td>
                                <td class="text-end">₱1,800</td>
                                <td>
                                    <span class="badge bg-info">Upcoming</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Carlo Santos</td>
                                <td>LN-0109</td>
                                <td class="text-muted">Sep 7, 2026</td>
                                <td class="text-end">₱950</td>
                                <td>
                                    <span class="badge bg-danger">Overdue</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
