<script setup>
import Notification from "@/Components/Notification.vue";
import { currencyFormat, formatDate } from "@/reuseables";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { nextTick, onMounted, ref } from "vue";

const modalView = ref(null);
let modalInstance = null;

const openViewModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalView.value);
        modalInstance.show();
    });
};

const modalForm = ref(null);
let modalInstanceForm = null;
const openModalForm = () => {
    nextTick(() => {
        modalInstanceForm = new Modal(modalForm.value);
        modalInstanceForm.show();
    });
};

const getYearService = ref("");
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
    openViewModal();
    getYearService.value = loan.yearservice;
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

const nextSchedule = ref({});

onMounted(async () => {
    for (const loan of props.listLoans) {
        if (loan.status !== 2) continue;
        const res = await axios.get(
            `/borrower/loans/${loan.id}/next-payment-schedule`,
        );
        nextSchedule.value[loan.id] = res.data.schedule;
    }
});
const principal = ref(500);

const getDay = (dateStr) => {
    if (!dateStr) return "";
    return new Date(dateStr).getDate();
};

const applyloanForm = useForm({
    borrower_id: "",
    loantype_id: "",
    amountborrowed: "",
    purpose: "",
});

const applyForLoan = () => {
    openModalForm();
};

const applyForLoanSubmit = () => {
    applyloanForm.borrower_id = props.profileData.id;
    applyloanForm.post(route("borrow.apply"), {
        onSuccess: () => {
            closeModal();
            applyloanForm.reset();
        },
    });
};

const closeModal = () => {
    modalInstanceForm?.hide();
    applyloanForm.reset();
};

const props = defineProps({
    profileData: Object,
    borrowerLoans: Array,
    listLoans: Array,
    loanTypes: Array,
});
</script>
<style>
td,
p {
    margin: 0;
}
.table-responsive {
    overflow: auto;
    max-height: 720px;
}

.table-responsive,
th {
    top: 0;
    z-index: 2;
    position: sticky;
}
.container {
    max-width: 110rem;
}
</style>
<template>
    <Notification />
    <section>
        <Head title="Borrower" />
        <div class="container-xl py-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h3 class="my-3">{{ profileData.fullname }}</h3>
                            <h4>
                                <span
                                    class="badge text-bg-success"
                                    v-if="profileData.status === 1"
                                    >Active</span
                                >
                                <span
                                    class="badge text-bg-danger"
                                    v-else-if="profileData.status === 2"
                                    >Inactive</span
                                >
                            </h4>
                        </div>
                        <div class="card-body">
                            <div
                                class="d-flex flex-row justify-content-center align-items-center gap-5"
                            >
                                <a
                                    class="btn btn-outline-danger"
                                    :href="route('borrow.logout')"
                                >
                                    <i class="fa-solid fa-power-off"></i> Logout
                                </a>
                                <button
                                    class="btn btn-outline-primary"
                                    @click="applyForLoan"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-4 mb-lg-0">
                        <div class="card-header">
                            <h6 class="card-title">Borrower Info</h6>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush rounded-3">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center p-3"
                                >
                                    <label>Employee ID</label>
                                    <p class="mb-0">{{ profileData.empid }}</p>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center p-3"
                                >
                                    <label>Year/s Service</label>
                                    <p
                                        class="mb-0"
                                        v-if="profileData.yearservice === 1"
                                    >
                                        1-4 Years
                                    </p>
                                    <p
                                        class="mb-0"
                                        v-if="profileData.yearservice === 2"
                                    >
                                        5-9 Years
                                    </p>
                                    <p
                                        class="mb-0"
                                        v-if="profileData.yearservice === 3"
                                    >
                                        10 Years Above
                                    </p>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center p-3"
                                >
                                    <label>Contact</label>
                                    <p class="mb-0">
                                        {{ profileData.contact }}
                                    </p>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center p-3"
                                >
                                    <label>Address</label>
                                    <p class="mb-0">
                                        {{ profileData.address }}
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <section class="row g-3 mb-4" aria-label="Statistics">
                        <div class="col-12 col-sm-6 col-xl-3">
                            <div class="card border shadow-sm h-100">
                                <div
                                    class="card-body d-flex justify-content-between align-items-start"
                                >
                                    <div>
                                        <div class="text-secondary small">
                                            Shared Capital
                                        </div>
                                        <div class="fs-5 fw-semibold">
                                            {{
                                                currencyFormat(
                                                    profileData.sharedcapital,
                                                )
                                            }}
                                        </div>
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
                                            Active Loans
                                        </div>
                                        <div class="fs-5 fw-semibold">
                                            {{ borrowerLoans.total_loans }}
                                        </div>
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
                                            Total Balance
                                        </div>
                                        <div class="fs-5 fw-semibold">
                                            {{
                                                currencyFormat(
                                                    borrowerLoans.total_balance,
                                                )
                                            }}
                                        </div>
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
                                            Total Paid
                                        </div>
                                        <div class="fs-5 fw-semibold">
                                            {{
                                                Number(
                                                    borrowerLoans.total_principal,
                                                ) +
                                                Number(
                                                    borrowerLoans.total_interest,
                                                )
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-info">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Reference</th>
                                    <th>Loan Type</th>
                                    <th>Amount Details</th>
                                    <th>Next Payment Details</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="text-center align-middle"
                                    v-for="(loan, index) in listLoans"
                                    :key="index"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                        <p>{{ loan.refno }}</p>
                                    </td>
                                    <td class="text-start">
                                        <p>{{ loan.plan }}</p>
                                    </td>
                                    <td class="text-start">
                                        <p>
                                            Total Paid:
                                            <strong>{{
                                                currencyFormat(loan.total_paid)
                                            }}</strong>
                                        </p>
                                        <p>
                                            Remaining Balance:
                                            <strong>{{
                                                currencyFormat(loan.currbalance)
                                            }}</strong>
                                        </p>
                                    </td>
                                    <td class="text-start">
                                        <div v-if="loan.status === 2">
                                            <div v-if="nextSchedule[loan.id]">
                                                <div
                                                    v-if="
                                                        getDay(
                                                            nextSchedule[
                                                                loan.id
                                                            ].date_due,
                                                        ) <= 15
                                                    "
                                                >
                                                    <p>
                                                        {{
                                                            formatDate(
                                                                nextSchedule[
                                                                    loan.id
                                                                ].date_due,
                                                            )
                                                        }}
                                                    </p>
                                                    <p class="text-primary">
                                                        <b
                                                            >Principal:
                                                            {{
                                                                currencyFormat(
                                                                    principal,
                                                                )
                                                            }}</b
                                                        >
                                                    </p>
                                                </div>
                                                <div v-else>
                                                    <p>
                                                        {{
                                                            formatDate(
                                                                nextSchedule[
                                                                    loan.id
                                                                ].date_due,
                                                            )
                                                        }}
                                                    </p>
                                                    <p class="text-primary">
                                                        <b
                                                            >Principal:
                                                            {{
                                                                currencyFormat(
                                                                    principal,
                                                                )
                                                            }}</b
                                                        >
                                                    </p>
                                                    <p class="text-danger">
                                                        <b
                                                            >Interest:
                                                            {{
                                                                currencyFormat(
                                                                    (loan.interest_rate /
                                                                        100) *
                                                                        loan.currbalance,
                                                                )
                                                            }}
                                                        </b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else>No Payment Details Yet</div>
                                    </td>
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
                                        <div
                                            class="d-flex gap-2 justify-content-center"
                                        >
                                            <button
                                                class="btn btn-sm btn-outline-secondary"
                                                @click="
                                                    viewLoanApplication(loan)
                                                "
                                            >
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal View the Loan Details -->
        <div class="modal fade" ref="modalView" tabindex="-1">
            <div
                class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered"
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
                            <div class="col-6 mb-4">
                                <i
                                    class="bi bi-check-circle-fill text-success fs-5"
                                ></i>
                                <div class="fw-semibold">Applied</div>
                                <div class="text-muted">
                                    {{
                                        getAppliedDate
                                            ? formatDate(getAppliedDate)
                                            : "--"
                                    }}
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <i
                                    class="bi bi-check-circle-fill text-success fs-5"
                                ></i>
                                <div class="fw-semibold">Approved</div>
                                <div class="text-muted">
                                    {{
                                        getApprovedDate
                                            ? formatDate(getApprovedDate)
                                            : "--"
                                    }}
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <i class="bi bi-circle text-secondary fs-5"></i>
                                <div class="fw-semibold text-muted">
                                    Released
                                </div>
                                <div class="text-muted">
                                    {{
                                        getReleasedDate
                                            ? formatDate(getReleasedDate)
                                            : "--"
                                    }}
                                </div>
                            </div>
                            <div class="col-6 mb-4">
                                <i class="bi bi-circle text-secondary fs-5"></i>
                                <div class="fw-semibold text-muted">
                                    Completed
                                </div>
                                <div class="text-muted">
                                    {{
                                        getCompletedDate
                                            ? formatDate(getAppliedDate)
                                            : "--"
                                    }}
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
                                    <div class="text-muted">Loan Plan</div>
                                    <div class="fw-semibold">
                                        {{ getPlanName }}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted">
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

        <!-- Modal Apply Loan -->
        <div
            class="modal fade"
            ref="modalForm"
            tabindex="-1"
            data-bs-keyboard="false"
            data-bs-backdrop="static"
        >
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form @submit.prevent="applyForLoanSubmit">
                        <div class="modal-header">
                            <h5 class="modal-title">Loan Application Form</h5>

                            <button
                                type="button"
                                class="btn-close btn-close-white-custom"
                                @click="closeModal"
                            ></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="p-4 modal-body-scroll">
                                <div class="mb-2">
                                    <span class="section-label"
                                        >Loan Details</span
                                    >
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col-md-8">
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
                                            :options="loanTypes"
                                            :reduce="(type) => type.id"
                                            label="name"
                                            placeholder="Select Loan Type"
                                            v-model="applyloanForm.loantype_id"
                                        >
                                            <template #option="type">
                                                <span
                                                    >{{ type.name }} [{{
                                                        type.interest_rate
                                                    }}% interest,
                                                    {{ type.penalty }}%
                                                    penalty]</span
                                                >
                                            </template>
                                            <template #selected-option="type">
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
                                    <div class="col-md-4">
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
                                                    applyloanForm.amountborrowed
                                                "
                                            />
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
                                        v-model="applyloanForm.purpose"
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
                                Apply Loan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
