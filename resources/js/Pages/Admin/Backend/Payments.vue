<script setup>
import { nextTick, ref, computed, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat, formatDate } from "@/reuseables";
import axios from "axios";
import Swal from "sweetalert2";

const currDate = new Date();

const modalRef = ref(null);
let modalInstance = null;
const openModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

const receiptModal = ref(null);
let modalInstanceView = null;
const openViewModal = () => {
    nextTick(() => {
        modalInstanceView = new Modal(receiptModal.value);
        modalInstanceView.show();
    });
};
const reciptRefno = ref("");
const reciptDatePaid = ref("");
const reciptBorrower = ref("");
const reciptType = ref("");
const reciptLoanRefno = ref("");
const reciptPrincipal = ref("");
const reciptInterest = ref("");
const reciptPenalty = ref("");
const reciptCapital = ref("");
const reciptTotalPaid = ref("");
const reciptRemainBalance = ref("");

const openReceiptModal = (pay) => {
    openViewModal();
    reciptRefno.value = pay.ofrec;
};

const closeModal = () => {
    modalInstance?.hide();
};

const paymentForm = useForm({
    loan_id: "",
    borrower_id: "",
    ofrec: "",
    principal: "",
    type_id: "",
    interest: "",
    capital: "",
    penalty: "",
});

const fetchLoanData = computed(() => {
    if (!paymentForm.loan_id) return;
    return props.loans.find((loan) => loan.id === paymentForm.loan_id);
});

const getPrincipal = ref("");
const getInterest = ref("");
const getPenalty = ref("");

const totalSummary = computed(() => {
    return (
        Number(getInterest.value) +
        Number(getPrincipal.value) +
        Number(getPenalty.value)
    );
});

const openPaymentModal = () => {
    openModal();
};

const currentSchedule = ref(null);
const offset = ref(null);

watch(
    () => paymentForm.loan_id,
    (loanid) => {
        if (!loanid) return;
        axios.get(`/admin/loans/${loanid}/current-schedule`).then((res) => {
            currentSchedule.value = res.data.schedule;
            offset.value = res.data.offset;
        });
    },
);

const addInterest = computed(() => {
    const day = new Date().getDate();
    const currbalance = Number(fetchLoanData.value?.currbalance) || 0;
    const rate = Number(fetchLoanData.value?.interest_rate) / 100;
    if (day <= 15) {
        return Number(0);
    } else {
        return currbalance * rate;
    }
});

const addPenalty = computed(() => {
    const dueDate = currentSchedule.value?.date_due
        ? new Date(currentSchedule.value.date_due)
        : null;
    const today = new Date();
    const penaltyRate = Number(fetchLoanData.value?.penalty) / 100 || 0;

    if (dueDate && today > dueDate) {
        return Number(fetchLoanData.value?.currbalance || 0) * penaltyRate;
    } else {
        return Number(0);
    }
});
const totalTodaySummary = computed(() => {
    const principal = Number(500);
    const withInterest = Number(addInterest.value) || 0;
    const withPenalty = Number(addPenalty.value) || 0;
    return principal + withInterest + withPenalty;
});

const paymentFormSubmit = () => {
    if (totalSummary.value === totalTodaySummary.value) {
        paymentForm.borrower_id = fetchLoanData.value?.borrower_id;
        paymentForm.principal = getPrincipal.value;
        paymentForm.interest = getInterest.value;
        paymentForm.penalty = getPenalty.value;
        paymentForm.type_id = fetchLoanData.value?.loantype_id;
        paymentForm.post(route("pays.store"), {
            onSuccess: () => {
                paymentForm.reset();
                closeModal();
            },
        });
    } else {
        Swal.fire({
            title: "Error!",
            text: "Summary and expected amount do not match.",
            icon: "error",
            timer: 1500,
            showConfirmButton: false,
        });
    }
};

const props = defineProps({
    loans: Array,
    payments: Array,
});
</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<style>
.loan-option {
    cursor: pointer;
}
.loan-option.selected {
    border-color: #198754 !important;
    background-color: #f0faf3;
}
#paymentSection {
    display: none;
}
.receipt-divider {
    border-top: 1px dashed #adb5bd;
}
@media print {
    .no-print {
        display: none !important;
    }
    .modal {
        position: absolute;
        left: 0;
        top: 0;
    }
}
</style>
<template>
    <Head title="Payments" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="font-display mb-0" style="font-size: 2rem">
                Loan Lists
            </h1>
        </div>
        <button class="btn btn-primary" @click="openPaymentModal">
            <i class="fa-solid fa-plus"></i> New Payment
        </button>
    </div>

    <div
        class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2"
    >
        <div class="input-group" style="max-width: 300px">
            <span class="input-group-text bg-white"
                ><i class="fa-solid fa-magnifying-glass"></i
            ></span>
            <input
                type="text"
                class="form-control"
                placeholder="Search loan type..."
            />
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
                    <th>Remaining Balance</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="text-center align-middle"
                    v-for="(pay, index) in payments"
                    :key="index"
                >
                    <td>{{ index + 1 }}</td>
                    <td class="text-start">
                        <p>
                            Loan Ref: <strong>{{ pay.refno }}</strong>
                        </p>
                        <p>
                            OR. #: <strong> {{ pay.ofrec }}</strong>
                        </p>
                    </td>
                    <td class="text-start">
                        <p>
                            Name: <strong>{{ pay.fullname }}</strong>
                        </p>
                        <p>
                            Plan: <strong>{{ pay.plan }}</strong>
                        </p>
                    </td>
                    <td class="text-start">
                        <p>
                            Principal:
                            <strong>{{ currencyFormat(pay.principal) }}</strong>
                        </p>
                        <p>
                            Interest:
                            <strong>
                                {{
                                    pay.interest
                                        ? currencyFormat(pay.interest)
                                        : currencyFormat(0)
                                }}</strong
                            >
                        </p>
                    </td>
                    <td class="text-start">
                        {{ currencyFormat(Number(pay.currbalance)) }}
                    </td>
                    <td>{{ formatDate(pay.created_at) }}</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button
                                class="btn btn-sm btn-outline-success"
                                @click="openReceiptModal(pay)"
                            >
                                <i class="fa-solid fa-receipt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <p v-for="value in currentSchedule">{{ value.date_due }}</p>
    <div class="modal fade" ref="modalRef" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <form @submit.prevent="paymentFormSubmit">
                    <div class="modal-header bg-light-subtle border-bottom">
                        <h5 class="modal-title text-success fw-bold">
                            Payment Form
                        </h5>
                        <span class="ms-auto me-3 fw-semibold" id="timestamp">{{
                            formatDate(currDate)
                        }}</span>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label
                                    for="borrower"
                                    class="form-label fw-semibold"
                                    >Select Borrower</label
                                >
                                <v-select
                                    :options="loans"
                                    :reduce="(loan) => loan.id"
                                    label="fulldetails"
                                    placeholder="Select Borrowers"
                                    v-model="paymentForm.loan_id"
                                ></v-select>
                            </div>
                            <div v-if="fetchLoanData">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label
                                            for="remainingBalance"
                                            class="form-label"
                                            >Remaining Balance</label
                                        >
                                        <div class="input-group">
                                            <span class="input-group-text"
                                                >₱</span
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                readonly
                                                :value="
                                                    currencyFormat(
                                                        fetchLoanData.currbalance,
                                                    )
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label
                                            for="sharedCapital"
                                            class="form-label"
                                            >Shared Capital</label
                                        >
                                        <div class="input-group">
                                            <span class="input-group-text"
                                                >₱</span
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                                readonly
                                                :value="
                                                    currencyFormat(
                                                        fetchLoanData.sharedcapital,
                                                    )
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label
                                                for="principal"
                                                class="form-label"
                                                >Principal</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="0"
                                                v-model="getPrincipal"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <label
                                                for="interest"
                                                class="form-label"
                                                >Interest</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="0"
                                                v-model="getInterest"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <label
                                                for="penalty"
                                                class="form-label"
                                                >Penalty</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="0"
                                                v-model="getPenalty"
                                            />
                                        </div>
                                        <div class="col-md-6">
                                            <label
                                                for="paidInCapital"
                                                class="form-label"
                                                >Paid-in Capital</label
                                            >
                                            <input
                                                type="number"
                                                class="form-control"
                                                placeholder="0"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="bg-light rounded p-3 h-100">
                                        <h6 class="text-muted fw-bold">
                                            Summary
                                        </h6>
                                        <div
                                            class="d-flex justify-content-between mb-2"
                                        >
                                            <span>Principal:</span
                                            ><span class="fw-semibold">{{
                                                currencyFormat(getPrincipal)
                                            }}</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between mb-2"
                                        >
                                            <span>Interest:</span
                                            ><span class="fw-semibold">{{
                                                currencyFormat(getInterest)
                                            }}</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between mb-2"
                                        >
                                            <span>Penalty:</span
                                            ><span
                                                class="fw-semibold"
                                                id="sumPenalty"
                                                >₱0.00</span
                                            >
                                        </div>
                                        <hr class="my-2" />
                                        <div
                                            class="d-flex justify-content-between fw-bold"
                                        >
                                            <span>Total:</span
                                            ><span>{{
                                                currencyFormat(totalSummary)
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="bg-light rounded p-3 h-100">
                                        <h6 class="text-primary fw-bold">
                                            Today's Expected
                                        </h6>
                                        <div
                                            class="d-flex justify-content-between mb-2"
                                        >
                                            <span>Principal:</span
                                            ><span class="fw-semibold">{{
                                                currencyFormat(500)
                                            }}</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between mb-2"
                                            v-if="currentSchedule"
                                        >
                                            <span>Interest:</span
                                            ><span class="fw-semibold">{{
                                                currencyFormat(addInterest)
                                            }}</span>
                                        </div>
                                        <div
                                            class="d-flex justify-content-between mb-2"
                                        >
                                            <span>Penalty:</span
                                            ><span class="fw-semibold">{{
                                                currencyFormat(addPenalty)
                                            }}</span>
                                        </div>
                                        <hr class="my-2" />
                                        <div
                                            class="d-flex justify-content-between fw-bold"
                                        >
                                            <span>Total:</span
                                            ><span>{{
                                                currencyFormat(
                                                    totalTodaySummary,
                                                )
                                            }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" ref="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title text-success fw-bold">
                        Payment Receipt
                    </h5>
                    <button
                        type="button"
                        class="btn-close no-print"
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h6 class="fw-bold mb-0">
                            FEMA Loan Management System
                        </h6>
                        <small class="text-muted"
                            >Official Payment Receipt</small
                        >
                    </div>

                    <div class="receipt-divider mb-3"></div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Reference No.</span>
                        <span class="fw-semibold">{{ reciptRefno }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Date Paid</span>
                        <span class="fw-semibold">Oct 27, 2025, 10:32 AM</span>
                    </div>

                    <div class="receipt-divider my-3"></div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Borrower</span>
                        <span class="fw-semibold">Marco, Echo E</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Loan Plan</span>
                        <span class="fw-semibold">Short Term Loan</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Loan Ref #</span>
                        <span class="fw-semibold">FEMA-LOAN-2026-00012</span>
                    </div>

                    <div class="receipt-divider my-3"></div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Principal</span>
                        <span>₱500.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Interest</span>
                        <span>₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Penalty</span>
                        <span>₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Paid-in Capital</span>
                        <span>₱0.00</span>
                    </div>

                    <div class="receipt-divider my-3"></div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Total Paid</span>
                        <span class="fw-bold text-success">₱500.00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Remaining Balance</span>
                        <span class="fw-semibold text-danger">₱4,000.00</span>
                    </div>

                    <div class="receipt-divider my-3"></div>

                    <div class="text-center small text-muted">
                        Received by: <strong>J. Santos</strong><br />
                        This receipt is system-generated.
                    </div>
                </div>

                <div class="modal-footer no-print">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Close
                    </button>
                    <button
                        type="button"
                        class="btn btn-success"
                        onclick="window.print()"
                    >
                        <i class="fa-solid fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
