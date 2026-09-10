<script setup>
import { nextTick, ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat, formatDate } from "@/reuseables";

const accordionOpen = ref(false);

const loanFormMode = ref("create");
const loanForm = useForm({
    borrower_id: "",
});

const modalRef = ref(null);
let modalInstance = null;
const openModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

const openLoanModalForm = () => {
    openModal();
};
</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<style>
.table-responsive {
    overflow: auto;
    max-height: 730px;
}
.table-responsive th {
    position: sticky;
    top: 0;
    z-index: 2;
}

.borrower-info-box {
    background-color: #f8f9fa;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    padding: 1rem 1.25rem;
}
.readonly-field {
    background-color: #eef1f2 !important;
    cursor: not-allowed;
}

.required-asterisk {
    color: #dc3545;
}
.active-loans-panel {
    background-color: #f8f9fa;
    border-left: 1px solid #e0e0e0;
    height: 100%;
    padding: 1.25rem 1rem;
    overflow-y: auto;
    max-height: 640px;
}
.active-loans-panel .panel-title {
    font-size: 0.85rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #495057;
}

@media (max-width: 767.98px) {
    .active-loans-panel {
        border-left: none;
        border-top: 1px solid #e0e0e0;
        max-height: 320px;
    }
}
</style>
<template>
    <Head title="Borrowers" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="font-display mb-0" style="font-size: 2rem">
                Loan Lists
            </h1>
        </div>
        <button class="btn btn-primary" @click="openLoanModalForm">
            <i class="fa-solid fa-plus"></i> New Loan Application
        </button>
    </div>
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button
                    class="accordion-button"
                    :class="{ collapsed: !accordionOpen }"
                    type="button"
                    @click="accordionOpen = !accordionOpen"
                >
                    Filters <i class="fa-solid fa-filter"></i>
                </button>
            </h2>
            <div v-if="accordionOpen" class="accordion-collapse">
                <div class="accordion-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="">Search Name</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Search Borrower's Name"
                                v-model="selectedName"
                            />
                        </div>
                        <div class="col-lg-4">
                            <label for="">Department</label>
                            <v-select
                                :options="depts"
                                :reduce="(dept) => dept.id"
                                label="name"
                                placeholder="Select Department"
                                v-model="selectedDept"
                            ></v-select>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Status</label>
                            <select
                                v-model="selectedStatus"
                                class="form-select"
                            >
                                <option value="">Select Status</option>
                                <option :value="0">Pending</option>
                                <option :value="1">Active</option>
                                <option :value="2">Disabled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-info">
                <tr class="text-center">
                    <th>#</th>
                    <th>Employee ID</th>
                    <th>Borrower's Name</th>
                    <th>Shared Capital</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center align-middle">
                    <td></td>
                    <td>qqwe</td>
                    <td class="text-right">Nmae</td>
                    <td>Capital</td>
                    <td>Name</td>
                    <td>
                        <span class="badge text-bg-secondary">Pending</span>
                        <span class="badge text-bg-success">Active</span>
                        <span class="badge text-bg-danger">Disabled</span>
                    </td>
                    <td></td>
                    <td class="text-center">
                        <button class="btn btn-outline-warning">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-outline-success">
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                        <button class="btn btn-outline-danger">
                            <i class="fa-solid fa-circle-minus"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div
        class="modal fade"
        ref="modalRef"
        tabindex="-1"
        data-bs-keyboard="false"
        data-bs-backdrop="static"
    >
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Loan Application Form</h5>
                    <button
                        type="button"
                        class="btn-close btn-close-white-custom"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body p-0">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="p-4 modal-body-scroll">
                                <form id="loanApplicationForm" novalidate>
                                    <div class="mb-4">
                                        <label
                                            for="borrowerSelect"
                                            class="form-label fw-semibold"
                                        >
                                            Borrower
                                            <span class="required-asterisk"
                                                >*</span
                                            >
                                        </label>
                                        <select
                                            class="form-select"
                                            id="borrowerSelect"
                                            required
                                        >
                                            <option value="" selected disabled>
                                                Select an option
                                            </option>
                                            <option value="1">
                                                Juan Dela Cruz
                                            </option>
                                            <option value="2">
                                                Maria Santos
                                            </option>
                                            <option value="3">
                                                Pedro Reyes
                                            </option>
                                        </select>
                                    </div>
                                    <div class="borrower-info-box mb-4">
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3"
                                        >
                                            <span class="section-label"
                                                >Borrower Information</span
                                            >
                                            <span
                                                class="badge badge-eligible"
                                                id="eligibilityBadge"
                                            >
                                                <i
                                                    class="bi bi-check-circle-fill me-1"
                                                ></i
                                                >Eligible
                                            </span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4 col-6">
                                                <label
                                                    class="form-label small mb-1"
                                                    >Shared Capital</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control readonly-field"
                                                    id="fieldSharedCapital"
                                                    value="₱25,000.00"
                                                    readonly
                                                    tabindex="-1"
                                                />
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <label
                                                    class="form-label small mb-1"
                                                    >Years of Service</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control readonly-field"
                                                    id="fieldYearsOfService"
                                                    value="3 years"
                                                    readonly
                                                    tabindex="-1"
                                                />
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <label
                                                    class="form-label small mb-1"
                                                    >Date Joined</label
                                                >
                                                <input
                                                    type="text"
                                                    class="form-control readonly-field"
                                                    id="fieldDateJoined"
                                                    value="Jan 14, 2023"
                                                    readonly
                                                    tabindex="-1"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <span class="section-label"
                                            >Loan Details</span
                                        >
                                    </div>

                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label
                                                for="loanPlan"
                                                class="form-label fw-semibold"
                                            >
                                                Loan Plan
                                                <span class="required-asterisk"
                                                    >*</span
                                                >
                                            </label>
                                            <select
                                                class="form-select"
                                                id="loanPlan"
                                                required
                                            >
                                                <option
                                                    value=""
                                                    selected
                                                    disabled
                                                >
                                                    Select an option
                                                </option>
                                                <option value="1">
                                                    Plan A [2% interest, 1%
                                                    penalty]
                                                </option>
                                                <option value="2">
                                                    Plan B [3% interest, 1.5%
                                                    penalty]
                                                </option>
                                                <option value="3">
                                                    Plan C [5% interest, 2%
                                                    penalty]
                                                </option>
                                            </select>
                                            <div class="mt-1">
                                                <small
                                                    >Plan [interest%,
                                                    Penalty%]</small
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label
                                                for="amountBorrowed"
                                                class="form-label fw-semibold"
                                            >
                                                Amount Borrowed
                                                <span class="required-asterisk"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"
                                                    >₱</span
                                                >
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    id="amountBorrowed"
                                                    placeholder="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    required
                                                />
                                            </div>
                                            <div class="mt-1">
                                                <small>
                                                    Max loanable:
                                                    <strong
                                                        >₱50,000.00</strong
                                                    ></small
                                                >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label
                                            for="purpose"
                                            class="form-label fw-semibold"
                                            >Purpose</label
                                        >
                                        <textarea
                                            class="form-control"
                                            id="purpose"
                                            rows="4"
                                            placeholder="Briefly describe the purpose of the loan"
                                        ></textarea>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="active-loans-panel">
                                <div
                                    class="d-flex justify-content-between align-items-center mb-3"
                                >
                                    <span class="panel-title"
                                        >Active Loans</span
                                    >
                                    <span
                                        class="badge bg-secondary"
                                        id="activeLoanBadgeCount"
                                        >1</span
                                    >
                                </div>
                                <div
                                    class="accordion accordion-flush"
                                    id="accordionFlushExample"
                                >
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <div
                                                class="d-flex justify-content-between align-items-center"
                                            ></div>
                                            <button
                                                class="accordion-button collapsed"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#flush-collapseOne"
                                            >
                                                <div>
                                                    <div class="fw-semibold">
                                                        Loan #LN-1042
                                                    </div>
                                                    <div class="helper-text">
                                                        Plan B &middot;
                                                        ₱15,000.00
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success-subtle"
                                                        >Current</span
                                                    >
                                                </div>
                                            </button>
                                        </h2>
                                        <div
                                            id="flush-collapseOne"
                                            class="accordion-collapse collapse"
                                            data-bs-parent="#accordionFlushExample"
                                        >
                                            <div class="accordion-body">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center"
                                                >
                                                    Remaining Balance
                                                    <span>₱9,200.00</span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center"
                                                >
                                                    Amount Paid
                                                    <span>₱5,800.00</span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center"
                                                >
                                                    Next Due Date
                                                    <span>Oct 5, 2026</span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center"
                                                >
                                                    Interest / Penalty
                                                    <span>3% / 1.5%</span>
                                                </li>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="no-active-loans d-none"
                                    id="noActiveLoansState"
                                >
                                    <i
                                        class="bi bi-inbox fs-3 d-block mb-2"
                                    ></i>
                                    No active loans for this borrower.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        form="loanApplicationForm"
                        class="btn btn-save text-white"
                    >
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
