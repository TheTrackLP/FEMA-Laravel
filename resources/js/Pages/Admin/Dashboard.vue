<script setup>
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    totalBorrowers: Object,
    totalActiveBorrowers: Object,
    totalPendingLoans: Object,
    totalLoanTypes: Object,
});
</script>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<template>
    <Head title="Dashboard" />
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
                        <div class="text-secondary small">Total Borrowers</div>
                        <div class="fs-3 fw-semibold">
                            {{ totalBorrowers }}
                        </div>
                    </div>
                    <div
                        class="rounded bg-primary-subtle text-primary px-3 py-2"
                    >
                        <i class="fa-solid fa-people-group"></i>
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
                        <div class="fs-3 fw-semibold">
                            {{ totalActiveBorrowers }}
                        </div>
                    </div>
                    <div
                        class="rounded bg-success-subtle text-success px-3 py-2"
                    >
                        <i class="fa-solid fa-user-check"></i>
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
                        <div class="text-secondary small">Pending Requests</div>
                        <div class="fs-3 fw-semibold">
                            {{ totalPendingLoans }}
                        </div>
                    </div>
                    <div
                        class="rounded bg-warning-subtle text-warning-emphasis px-3 py-2"
                    >
                        <i class="fa-solid fa-hourglass"></i>
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
                        <div class="text-secondary small">Total Loan Types</div>
                        <div class="fs-3 fw-semibold">{{ totalLoanTypes }}</div>
                    </div>
                    <div
                        class="rounded bg-secondary-subtle text-secondary-emphasis px-3 py-2"
                    >
                        <i class="fa-solid fa-folder-open"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
