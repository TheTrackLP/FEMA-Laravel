<script setup>
import { Head } from "@inertiajs/vue3";
import { currencyFormat } from "@/reuseables";
import { ref, computed } from "vue";

const selectedMonthYear = ref("");

const filterMonthYear = computed(() => {
    const dateQuery = selectedMonthYear.value;
    return props.payees.filter((date) => {
        const matchDateQuery = !dateQuery || date.month === dateQuery;

        return matchDateQuery;
    });
});

const props = defineProps({
    loanTypes: Array,
    payees: Array,
});
</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
export default {
    layout: AdminLayout,
};
</script>
<template>
    <Head title="Reports" />
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="font-display mb-0" style="font-size: 2rem">Reports</h1>
        </div>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <input
                type="month"
                class="form-control"
                v-model="selectedMonthYear"
            />
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-info text-center align-middle">
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Borrower Name</th>
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
                <tr>
                    <template v-for="(type, index) in loanTypes" :key="index">
                        <th>print</th>
                        <th>INT</th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(payee, index) in filterMonthYear" :key="index">
                    <td>#</td>
                    <td>{{ payee.fullname }}</td>
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
