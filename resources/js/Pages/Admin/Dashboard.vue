<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<template>
    <AdminLayout>
        <div class="mb-4">
            <h1 class="h4 mb-1">Dashboard</h1>
            <p class="text-secondary mb-0">Welcome back, Admin.</p>
        </div>

        <!-- Statistics Cards -->
        <section class="row g-3 mb-4" aria-label="Statistics">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border shadow-sm h-100">
                    <div
                        class="card-body d-flex justify-content-between align-items-start"
                    >
                        <div>
                            <div class="text-secondary small">Total Users</div>
                            <div class="fs-3 fw-semibold">1,248</div>
                            <div class="small text-success">+24 this month</div>
                        </div>
                        <div
                            class="rounded bg-primary-subtle text-primary px-3 py-2"
                        >
                            <i class="bi bi-people fs-4" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border shadow-sm h-100">
                    <div
                        class="card-body d-flex justify-content-between align-items-start"
                    >
                        <div>
                            <div class="text-secondary small">Active Users</div>
                            <div class="fs-3 fw-semibold">1,086</div>
                            <div class="small text-secondary">
                                87% of all users
                            </div>
                        </div>
                        <div
                            class="rounded bg-success-subtle text-success px-3 py-2"
                        >
                            <i
                                class="bi bi-person-check fs-4"
                                aria-hidden="true"
                            ></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border shadow-sm h-100">
                    <div
                        class="card-body d-flex justify-content-between align-items-start"
                    >
                        <div>
                            <div class="text-secondary small">
                                Pending Requests
                            </div>
                            <div class="fs-3 fw-semibold">37</div>
                            <div class="small text-warning-emphasis">
                                Awaiting approval
                            </div>
                        </div>
                        <div
                            class="rounded bg-warning-subtle text-warning-emphasis px-3 py-2"
                        >
                            <i
                                class="bi bi-hourglass-split fs-4"
                                aria-hidden="true"
                            ></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border shadow-sm h-100">
                    <div
                        class="card-body d-flex justify-content-between align-items-start"
                    >
                        <div>
                            <div class="text-secondary small">
                                Total Records
                            </div>
                            <div class="fs-3 fw-semibold">9,764</div>
                            <div class="small text-secondary">
                                Across 12 departments
                            </div>
                        </div>
                        <div
                            class="rounded bg-secondary-subtle text-secondary-emphasis px-3 py-2"
                        >
                            <i
                                class="bi bi-folder2-open fs-4"
                                aria-hidden="true"
                            ></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick Actions -->
        <section
            class="card border shadow-sm mb-4"
            aria-labelledby="quick-actions-title"
        >
            <div class="card-body">
                <h2 class="h6 mb-3" id="quick-actions-title">Quick Actions</h2>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2" aria-hidden="true"></i
                        >Add User
                    </button>
                    <button type="button" class="btn btn-outline-primary">
                        <i
                            class="bi bi-person-badge me-2"
                            aria-hidden="true"
                        ></i
                        >Add Employee
                    </button>
                    <button type="button" class="btn btn-outline-primary">
                        <i
                            class="bi bi-file-earmark-bar-graph me-2"
                            aria-hidden="true"
                        ></i
                        >Generate Report
                    </button>
                    <button type="button" class="btn btn-outline-primary">
                        <i
                            class="bi bi-bar-chart-line me-2"
                            aria-hidden="true"
                        ></i
                        >View Reports
                    </button>
                </div>
            </div>
        </section>

        <!-- Recent Activity -->
        <section
            class="card border shadow-sm"
            aria-labelledby="recent-activity-title"
        >
            <div class="card-header bg-white py-3">
                <h2 class="h6 mb-0" id="recent-activity-title">
                    Recent Activity
                </h2>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">Action</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>John Doe</td>
                            <td>Added new employee</td>
                            <td class="text-nowrap">Oct 7, 2026</td>
                            <td>
                                <span class="badge bg-success">Completed</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Jane Smith</td>
                            <td>Updated profile</td>
                            <td class="text-nowrap">Oct 7, 2026</td>
                            <td>
                                <span class="badge bg-success">Completed</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Mark Santos</td>
                            <td>Submitted request</td>
                            <td class="text-nowrap">Oct 6, 2026</td>
                            <td>
                                <span class="badge bg-warning text-dark"
                                    >Pending</span
                                >
                            </td>
                        </tr>
                        <tr>
                            <td>Angela Reyes</td>
                            <td>Requested department transfer</td>
                            <td class="text-nowrap">Oct 6, 2026</td>
                            <td>
                                <span class="badge bg-warning text-dark"
                                    >Pending</span
                                >
                            </td>
                        </tr>
                        <tr>
                            <td>Liza Fernandez</td>
                            <td>Cancelled purchase request</td>
                            <td class="text-nowrap">Oct 5, 2026</td>
                            <td>
                                <span class="badge bg-danger">Cancelled</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </AdminLayout>
</template>
