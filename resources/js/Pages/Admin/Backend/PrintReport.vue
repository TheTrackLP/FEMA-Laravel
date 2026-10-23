<script setup>
import { currencyFormat } from "@/reuseables";
import { Head } from "@inertiajs/vue3";

const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long" };
    return new Date(dateString).toLocaleDateString("en-US", options);
};

const props = defineProps({
    loanTypes: Array,
    reports: Array,
    filters: Array,
});
</script>

<style>
.report-page,
.logo {
    display: block;
    height: 150px;
    margin: 20px auto;
    border-radius: 50%;
    object-fit: cover;
}
@media print {
    @page {
        size: 13in 8.5in;
        margin: 5mm;
    }

    .table-responsive {
        overflow: visible !important;
        width: 100% !important;
    }
    .report-table {
        width: 100% !important;
        table-layout: fixed;
        font-size: 8px;
    }

    .report-table th,
    .report-table td {
        padding: 2px 3px !important;
        word-wrap: break-word;
        white-space: normal;
    }
    table,
    tr,
    td,
    th {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .text-truncate,
    th,
    td {
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: clip !important;
        word-break: break-word;
    }
}
</style>
<template>
    <Head :title="formatDate(filters.report_date)" />
    <div class="top-head my-4">
        <div class="row">
            <div class="col-1 report-page" style="margin-top: -1rem">
                <img class="logo" src="/img/femalogo.png" alt="" />
            </div>
            <div class="col-11" style="margin-left: -2.5rem">
                <div class="text-center">
                    <h5><b>FILAMER CHRISTIAN UNIVERSITY</b></h5>
                    <h5>Roxas City, Philippines 5800</h5>
                    <h5><b>FEMA - FCU Employee Mutual Association</b></h5>
                </div>
                <h3 class="text-center">
                    <strong
                        >As of
                        <u class="uppercase">{{
                            formatDate(filters.report_date)
                        }}</u></strong
                    >
                </h3>
            </div>
        </div>
    </div>
    <div class="table-responsive mb-4">
        <table class="table report-table table-hover table-bordered">
            <thead class="table-info text-center align-middle">
                <tr>
                    <th rowspan="2">#</th>
                    <th class="text-truncate" rowspan="2">Borrower Name</th>
                    <th
                        colspan="2"
                        v-for="(type, index) in loanTypes"
                        :key="index"
                    >
                        {{ type.name }}
                    </th>
                    <th rowspan="2">Paid-in</th>
                    <th rowspan="2">Penalty</th>
                    <th rowspan="2">Total</th>
                </tr>
                <tr class="text-center align-middle">
                    <template v-for="(type, index) in loanTypes" :key="index">
                        <th>PRIN</th>
                        <th>INT</th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="text-center align-middle"
                    v-for="(payee, index) in reports"
                    :key="index"
                >
                    <td>{{ index + 1 }}</td>
                    <td class="text-start text-truncate">
                        {{ payee.fullname }}
                    </td>
                    <template v-for="(type, index) in loanTypes" :key="index">
                        <td>{{ payee.loans[type.id]?.principal ?? "-" }}</td>
                        <td>{{ payee.loans[type.id]?.interest ?? "-" }}</td>
                    </template>
                    <td>{{ currencyFormat(payee.total_capital) }}</td>
                    <td>{{ currencyFormat(payee.total_penalty) }}</td>
                    <td>
                        {{ currencyFormat(payee.overalltotal) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
