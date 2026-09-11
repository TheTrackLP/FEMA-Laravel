<script setup>
import { nextTick, ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat, formatDate } from "@/reuseables";

const accordionOpen = ref(false);
const accordionOpenLoan = ref(false);

const loanFormMode = ref("create");
const loanForm = useForm({
    borrower_id: "",
    loantype_id: "",
    amountborrowed: "",
    purpose: "",
    status: "",
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
    loanFormMode.value = "create";
};

const fetctApplication = (loan) => {
    loanFormMode.value = "edit";
    loanForm.id = loan.id;
    loanForm.borrower_id = loan.borrower_id;
    loanForm.loantype_id = loan.loantype_id;
    loanForm.amountborrowed = loan.amountborrowed;
    loanForm.purpose = loan.purpose;
    loanForm.status = loan.status;
    openModal();
};

const fetchBorrower = computed(() => {
    if (!loanForm.borrower_id) return;
    return props.borrowers.find((borrow) => borrow.id === loanForm.borrower_id);
});

const loanApplicationForm = () => {
    if (loanFormMode.value === "create") {
        loanFormMode.value = "create";
        loanForm.post(route("loans.store"), {
            onSuccess: () => {
                loanForm.reset();
            },
        });
    } else {
        loanFormMode.value = "edit";
        loanForm.post(route("loans.update", loanForm.id), {
            onSuccess: () => {
                loanForm.reset();
            },
        });
    }
};

const props = defineProps({
    borrowers: Array,
    types: Array,
    loans: Array,
});
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
    <Head title="Loans" />
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
                    <th>Reference</th>
                    <th>Borrower's Details</th>
                    <th>Amount Details</th>
                    <th>Next Payment Details</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="text-center align-middle"
                    v-for="(loan, index) in loans"
                    :key="index"
                >
                    <td>{{ index + 1 }}</td>
                    <td>{{ loan.refno }}</td>
                    <td class="text-start">
                        <p>
                            Name: <strong>{{ loan.fullname }}</strong>
                        </p>
                        <p>
                            Plan: <strong>{{ loan.plan }}</strong>
                        </p>
                    </td>
                    <td class="text-start">
                        <p>
                            Total Paid: <strong>{{ loan.fullname }}</strong>
                        </p>
                        <p>
                            Remaining Balance: <strong>{{ loan.plan }}</strong>
                        </p>
                    </td>
                    <td>Name</td>
                    <td>
                        <span
                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"
                            v-if="loan.status === 0"
                            >Pending</span
                        >
                        <span
                            class="badge bg-info-subtle text-info border border-info-subtle"
                            v-else-if="loan.status === 1"
                            >Approved</span
                        >
                        <span
                            class="badge bg-primary-subtle text-primary border border-primary-subtle"
                            v-else-if="loan.status === 2"
                            >Released</span
                        >
                        <span
                            class="badge bg-success-subtle text-success border border-success-subtle"
                            v-else-if="loan.status === 3"
                            >Active</span
                        >
                        <span
                            class="badge bg-danger-subtle text-danger border border-danger-subtle"
                            v-else-if="loan.status === 4"
                            >Denied</span
                        >
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-outline-secondary">
                                View
                            </button>
                            <template v-if="loan.status === 0">
                                <button
                                    class="btn btn-sm btn-outline-secondary"
                                    @click="fetctApplication(loan)"
                                >
                                    Edit
                                </button>
                            </template>
                        </div>
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
                <form @submit.prevent="loanApplicationForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Loan Application Form</h5>
                        <input type="text" v-model="loanForm.id" />
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
                                        <v-select
                                            :options="borrowers"
                                            :reduce="(borrow) => borrow.id"
                                            label="fullname"
                                            placeholder="Select Borrower"
                                            v-model="loanForm.borrower_id"
                                        ></v-select>
                                    </div>
                                    <div
                                        class="borrower-info-box mb-4"
                                        v-if="fetchBorrower"
                                    >
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3"
                                        >
                                            <span class="section-label"
                                                >Borrower Information</span
                                            >
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
                                                    readonly
                                                    :value="
                                                        currencyFormat(
                                                            fetchBorrower.sharedcapital,
                                                        )
                                                    "
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
                                                    value="1-4 years"
                                                    readonly
                                                    v-if="
                                                        fetchBorrower.yearservice ===
                                                        1
                                                    "
                                                />
                                                <input
                                                    type="text"
                                                    class="form-control readonly-field"
                                                    value="5-9 years"
                                                    readonly
                                                    v-else-if="
                                                        fetchBorrower.yearservice ===
                                                        2
                                                    "
                                                />
                                                <input
                                                    type="text"
                                                    class="form-control readonly-field"
                                                    value="10-Above years"
                                                    readonly
                                                    v-if="
                                                        fetchBorrower.yearservice ===
                                                        3
                                                    "
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
                                                    :value="
                                                        formatDate(
                                                            fetchBorrower.datejoined,
                                                        )
                                                    "
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
                                        <div class="col-md-7">
                                            <label
                                                for="loanPlan"
                                                class="form-label fw-semibold"
                                            >
                                                Loan Plan
                                                <span class="required-asterisk"
                                                    >*</span
                                                >
                                            </label>
                                            <v-select
                                                :options="types"
                                                :reduce="(type) => type.id"
                                                label="name"
                                                placeholder="Select Loan Type"
                                                v-model="loanForm.loantype_id"
                                            >
                                                <template #option="type">
                                                    <span
                                                        >{{ type.name }} [{{
                                                            type.interest_rate
                                                        }}% interest,
                                                        {{ type.penalty }}%
                                                        penalty]</span
                                                    >
                                                </template></v-select
                                            >
                                            <div class="mt-1">
                                                <small
                                                    >Plan [interest%,
                                                    Penalty%]</small
                                                >
                                            </div>
                                        </div>

                                        <div class="col-md-5">
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
                                                    placeholder="0.00"
                                                    min="0"
                                                    step="0.01"
                                                    required
                                                    v-model="
                                                        loanForm.amountborrowed
                                                    "
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
                                            rows="4"
                                            placeholder="Briefly describe the purpose of the loan"
                                            v-model="loanForm.purpose"
                                        ></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label
                                            for="status"
                                            class="form-label fw-semibold"
                                            >Status</label
                                        >
                                        <select
                                            class="form-select"
                                            v-model="loanForm.status"
                                        >
                                            <option value="0">Pending</option>
                                            <option value="1">Approved</option>
                                            <option value="2">Active</option>
                                            <option value="3">Complete</option>
                                            <option value="4">Denied</option>
                                        </select>
                                    </div>
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
                                                    :class="{
                                                        collapsed:
                                                            !accordionOpenLoan,
                                                    }"
                                                    @click="
                                                        accordionOpenLoan =
                                                            !accordionOpenLoan
                                                    "
                                                >
                                                    <div>
                                                        <div
                                                            class="fw-semibold"
                                                        >
                                                            Loan #LN-1042
                                                        </div>
                                                        <div
                                                            class="helper-text"
                                                        >
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
                                                class="accordion-collapse"
                                                v-if="accordionOpenLoan"
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
                            class="btn btn-success text-white"
                        >
                            {{
                                loanForm.processing
                                    ? "Saving.."
                                    : loanFormMode === "create"
                                      ? "Add Application"
                                      : "Save Changes"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
