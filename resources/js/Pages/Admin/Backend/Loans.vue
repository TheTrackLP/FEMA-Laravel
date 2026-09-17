<script setup>
import { nextTick, ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat, formatDate } from "@/reuseables";

const accordionOpen = ref(false);
const readonlyField = ref(true);
const statusView = ref(false);

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

const closeModal = () => {
    modalInstance?.hide();
};

const modalView = ref(null);
let modalInstanceView = null;
const openModalView = () => {
    nextTick(() => {
        modalInstanceView = new Modal(modalView.value);
        modalInstanceView.show();
    });
};

const openLoanModalForm = () => {
    openModal();
    loanFormMode.value = "create";
    readonlyField.value = false;
    statusView.value = false;
    loanForm.reset();
};

const fetctApplication = (loan) => {
    loanFormMode.value = "edit";
    loanForm.id = loan.id;
    loanForm.borrower_id = loan.borrower_id;
    loanForm.loantype_id = loan.loantype_id;
    loanForm.amountborrowed = loan.amountborrowed;
    loanForm.purpose = loan.purpose;
    loanForm.status = loan.status;
    readonlyField.value = true;
    statusView.value = true;
    openModal();
};

const getBorrowerName = ref("");
const getSharedCap = ref("");
const getYearService = ref("");
const getYearJoined = ref("");
const getPlanName = ref("");
const getAmountBorrowed = ref("");
const getCurrBalance = ref("");
const getPurpose = ref("");
const getRefNo = ref("");
const getAppliedDate = ref("");
const getApprovedDate = ref("");
const getReleasedDate = ref("");
const getCompletedDate = ref("");
const getStatus = ref("");

const viewLoanApplication = (loan) => {
    openModalView();
    getBorrowerName.value = loan.fullname;
    getSharedCap.value = loan.sharedcapital;
    getYearService.value = loan.yearservice;
    getYearJoined.value = loan.datejoined;
    getPlanName.value = loan.fullplan;
    getAmountBorrowed.value = loan.amountborrowed;
    getCurrBalance.value = loan.currbalance;
    getPurpose.value = loan.purpose;
    getRefNo.value = loan.refno;
    getAppliedDate.value = loan.date_applied;
    getApprovedDate.value = loan.date_approved;
    getReleasedDate.value = loan.date_released;
    getCompletedDate.value = loan.date_completed;
    getStatus.value = loan.status;
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
                closeModal();
            },
        });
    } else {
        loanFormMode.value = "edit";
        loanForm.post(route("loans.update", loanForm.id), {
            onSuccess: () => {
                loanForm.reset();
                closeModal();
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

.purpose-text {
    font-size: 1rem;
    color: #343a40;
    min-height: 100px;
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
                            Total Paid: <strong>{{ loan.total_paid }}</strong>
                        </p>
                        <p>
                            Remaining Balance:
                            <strong>{{
                                currencyFormat(loan.currbalance)
                            }}</strong>
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
                            >Released</span
                        >
                        <span
                            class="badge bg-danger-subtle text-danger border border-danger-subtle"
                            v-else-if="loan.status === 4"
                            >Denied</span
                        >
                    </td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button
                                class="btn btn-sm btn-outline-secondary"
                                @click="viewLoanApplication(loan)"
                            >
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <button
                                v-if="loan.status === 0 || loan.status === 1"
                                class="btn btn-sm btn-outline-primary"
                                @click="fetctApplication(loan)"
                            >
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
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
                        <input type="hidden" v-model="loanForm.id" />
                        <button
                            type="button"
                            class="btn-close btn-close-white-custom"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="p-4 modal-body-scroll">
                            <div class="mb-4">
                                <label
                                    for="borrowerSelect"
                                    class="form-label fw-semibold"
                                >
                                    Borrower
                                    <span class="required-asterisk">*</span>
                                </label>
                                <v-select
                                    :options="borrowers"
                                    :reduce="(borrow) => borrow.id"
                                    label="fullname"
                                    placeholder="Select Borrower"
                                    v-model="loanForm.borrower_id"
                                    :disabled="readonlyField"
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
                                        <label class="form-label small mb-1"
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
                                        <label class="form-label small mb-1"
                                            >Years of Service</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control readonly-field"
                                            value="1-4 years"
                                            readonly
                                            v-if="
                                                fetchBorrower.yearservice === 1
                                            "
                                        />
                                        <input
                                            type="text"
                                            class="form-control readonly-field"
                                            value="5-9 years"
                                            readonly
                                            v-else-if="
                                                fetchBorrower.yearservice === 2
                                            "
                                        />
                                        <input
                                            type="text"
                                            class="form-control readonly-field"
                                            value="10-Above years"
                                            readonly
                                            v-if="
                                                fetchBorrower.yearservice === 3
                                            "
                                        />
                                    </div>
                                    <div class="col-md-4 col-6">
                                        <label class="form-label small mb-1"
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
                                <span class="section-label">Loan Details</span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label
                                        for="loanPlan"
                                        class="form-label fw-semibold"
                                    >
                                        Loan Plan
                                        <span class="required-asterisk">*</span>
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
                                            >Plan [interest%, Penalty%]</small
                                        >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label
                                        for="amountBorrowed"
                                        class="form-label fw-semibold"
                                    >
                                        Amount Borrowed
                                        <span class="required-asterisk">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input
                                            type="number"
                                            class="form-control"
                                            placeholder="0.00"
                                            min="0"
                                            step="0.01"
                                            required
                                            v-model="loanForm.amountborrowed"
                                        />
                                    </div>
                                    <div class="mt-1">
                                        <small>
                                            Max loanable:
                                            <strong>₱50,000.00</strong></small
                                        >
                                    </div>
                                </div>
                                <div class="col-md-4" v-if="statusView">
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
                                            <option value="2">Released</option>
                                            <option value="3">Complete</option>
                                            <option value="4">Denied</option>
                                        </select>
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
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            @click="closeModal"
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

    <div class="modal fade" ref="modalView" tabindex="-1">
        <div
            class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered"
        >
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h4 class="modal-title mb-0">Loan Details</h4>
                        <small class="">{{ getRefNo }}</small>
                    </div>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body">
                    <div
                        class="d-flex justify-content-between align-items-center mb-3"
                    >
                        <span
                            class="text-uppercase text-muted small fw-semibold"
                            >Status</span
                        >
                        <span
                            class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"
                            v-if="getStatus === 0"
                            >Pending</span
                        >
                        <span
                            class="badge bg-info-subtle text-info border border-info-subtle"
                            v-else-if="getStatus === 1"
                            >Approved</span
                        >
                        <span
                            class="badge bg-primary-subtle text-primary border border-primary-subtle"
                            v-else-if="getStatus === 2"
                            >Released</span
                        >
                        <span
                            class="badge bg-success-subtle text-success border border-success-subtle"
                            v-else-if="getStatus === 3"
                            >Released</span
                        >
                        <span
                            class="badge bg-danger-subtle text-danger border border-danger-subtle"
                            v-else-if="getStatus === 4"
                            >Denied</span
                        >
                    </div>
                    <div class="row text-center">
                        <div class="col">
                            <i
                                class="bi bi-check-circle-fill text-success fs-5"
                            ></i>
                            <div class="small fw-semibold">Applied</div>
                            <div class="small text-muted">
                                {{
                                    getAppliedDate
                                        ? formatDate(getAppliedDate)
                                        : "--"
                                }}
                            </div>
                        </div>
                        <div class="col">
                            <i
                                class="bi bi-check-circle-fill text-success fs-5"
                            ></i>
                            <div class="small fw-semibold">Approved</div>
                            <div class="small text-muted">
                                {{
                                    getApprovedDate
                                        ? formatDate(getAppliedDate)
                                        : "--"
                                }}
                            </div>
                        </div>
                        <div class="col">
                            <i class="bi bi-circle text-secondary fs-5"></i>
                            <div class="small fw-semibold text-muted">
                                Released
                            </div>
                            <div class="small text-muted">
                                {{
                                    getReleasedDate
                                        ? formatDate(getAppliedDate)
                                        : "--"
                                }}
                            </div>
                        </div>
                        <div class="col">
                            <i class="bi bi-circle text-secondary fs-5"></i>
                            <div class="small fw-semibold text-muted">
                                Completed
                            </div>
                            <div class="small text-muted">
                                {{
                                    getCompletedDate
                                        ? formatDate(getAppliedDate)
                                        : "--"
                                }}
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="my-5">
                        <h6
                            class="text-uppercase text-muted small fw-semibold mb-3"
                        >
                            Borrower Information
                        </h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="small text-muted">Borrower</div>
                                <div class="fw-semibold">
                                    {{ getBorrowerName }}
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="small text-muted">
                                    Shared Capital
                                </div>
                                <div class="fw-semibold">
                                    {{ currencyFormat(getSharedCap) }}
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="small text-muted">
                                    Years of Service
                                </div>
                                <div
                                    class="fw-semibold"
                                    v-if="getYearService === 1"
                                >
                                    1-4 years
                                </div>
                                <div
                                    class="fw-semibold"
                                    v-else-if="getYearService === 2"
                                >
                                    5-9 years
                                </div>
                                <div
                                    class="fw-semibold"
                                    v-else-if="getYearService === 3"
                                >
                                    10 Above years
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="small text-muted">Date Joined</div>
                                <div class="fw-semibold">
                                    {{ formatDate(getYearJoined) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr />
                    <div class="my-4">
                        <h6
                            class="text-uppercase text-muted small fw-semibold mb-3"
                        >
                            Loan Details
                        </h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="small text-muted">Loan Plan</div>
                                <div class="fw-semibold">
                                    {{ getPlanName }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">
                                    Amount Borrowed
                                </div>
                                <div class="fw-semibold">
                                    {{ currencyFormat(getAmountBorrowed) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">
                                    Current Balance
                                </div>
                                <div class="fw-semibold">
                                    {{ currencyFormat(getCurrBalance) }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="small text-muted mb-1">Purpose</div>
                            <p
                                class="border rounded p-2 bg-light mb-0 purpose-text"
                            >
                                {{ getPurpose }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
