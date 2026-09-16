<script setup>
import { nextTick, ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat, formatDate } from "@/reuseables";

const modalRef = ref(null);
let modalInstance = null;
const openModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
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

const openPaymentModal = () => {
    openModal();
};

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

const interest = computed(() => {
    const currbalance = Number(fetchLoanData.value?.currbalance) || 0;
    const rate = Number(fetchLoanData.value?.interest_rate) / 100;
    return currbalance * rate;
});

const totalTodaySummary = computed(() => {
    return interest.value + 500;
});

const props = defineProps({
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
                <tr class="text-center align-middle">
                    <td>1</td>
                    <td>
                        <p>Loan Ref: <strong></strong></p>
                        <p>OR. #: <strong></strong></p>
                    </td>
                    <td class="text-start">
                        <p>Name: <strong></strong></p>
                        <p>Plan: <strong></strong></p>
                    </td>
                    <td class="text-start">
                        <p>Principal: <strong></strong></p>
                        <p>Interest: <strong></strong></p>
                    </td>
                    <td class="text-start">Name</td>
                    <td>date</td>
                    <td class="text-center">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-sm btn-outline-secondary">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" ref="modalRef" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light-subtle border-bottom">
                    <h5 class="modal-title text-success fw-bold">
                        Payment Form
                    </h5>
                    <span class="ms-auto me-3 fw-semibold" id="timestamp"
                        >—</span
                    >
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label for="borrower" class="form-label fw-semibold"
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
                    </div>
                    <div v-if="fetchLoanData">
                        <hr />
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="remainingBalance" class="form-label"
                                    >Remaining Balance</label
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
                            <div class="col-md-3">
                                <label for="sharedCapital" class="form-label"
                                    >Shared Capital</label
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
                                        <label for="interest" class="form-label"
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
                                        <label for="penalty" class="form-label"
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
                                    <h6 class="text-muted fw-bold">Summary</h6>
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
                                    >
                                        <span>Interest:</span
                                        ><span class="fw-semibold">{{
                                            currencyFormat(interest)
                                        }}</span>
                                    </div>
                                    <div
                                        class="d-flex justify-content-between mb-2"
                                    >
                                        <span>Penalty:</span
                                        ><span
                                            class="fw-semibold"
                                            id="expPenalty"
                                            >—</span
                                        >
                                    </div>
                                    <hr class="my-2" />
                                    <div
                                        class="d-flex justify-content-between fw-bold"
                                    >
                                        <span>Total:</span
                                        ><span>{{
                                            currencyFormat(totalTodaySummary)
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
                    <button
                        type="button"
                        class="btn btn-success"
                        id="savePaymentBtn"
                    >
                        Save Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
