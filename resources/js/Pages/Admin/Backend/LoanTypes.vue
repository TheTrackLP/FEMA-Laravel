<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const loanTypesFormMode = ref("create");
const loanTypesForm = useForm({
    name: "",
    desc: "",
    interest_rate: "",
    penalty: "",
    isActive: "",
});

const fetchType = (type) => {
    loanTypesFormMode.value = "edit";
    loanTypesForm.id = type.id;
    loanTypesForm.name = type.name;
    loanTypesForm.desc = type.desc;
    loanTypesForm.interest_rate = type.interest_rate;
    loanTypesForm.penalty = type.penalty;
    loanTypesForm.isActive = Boolean(type.isActive);
};

const loanTypeFormSubmit = () => {
    if (loanTypesFormMode.value === "create") {
        loanTypesFormMode.value = "create";
        loanTypesForm.post(route("types.store"), {
            onSuccess: () => {
                loanTypesForm.reset();
                loanTypesFormMode.value = "create";
            },
        });
    } else {
        loanTypesFormMode.value = "edit";
        loanTypesForm.post(route("types.update", loanTypesForm.id), {
            onSuccess: () => {
                loanTypesForm.reset();
                loanTypesFormMode.value = "create";
            },
        });
    }
};

const clearForm = () => {
    loanTypesFormMode.value = "create";
    loanTypesForm.reset();
};

const loanTypeStatus = (type) => {
    loanTypesForm.post(route("type.stat", type.id));
};

const searchTypeName = ref("");
const searchTypeStatus = ref("");
const filterTypes = computed(() => {
    const typeNameQuery = searchTypeName.value.toLowerCase().trim();
    const typeStatusQuery = searchTypeStatus.value;

    return props.types.filter((type) => {
        const matchTypeName =
            !typeNameQuery || type.name.toLowerCase().includes(typeNameQuery);
        const matchTypeStatus =
            !typeStatusQuery ||
            Number(type.isActive) === Number(typeStatusQuery);

        return matchTypeName && matchTypeStatus;
    });
});

const props = defineProps({
    types: Array,
});
</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<style>
.title-cell {
    max-width: 340px;
}
.title-cell .t-truncate {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.table-responsive {
    overflow: auto;
    max-height: 625px;
}
.table-responsive th {
    position: sticky;
    top: 0;
    z-index: 2;
}
</style>
<template>
    <Head title="Loan Types" />
    <div
        class="d-flex justify-content-between align-items-center page-header flex-wrap gap-2"
    >
        <div>
            <h4 class="fw-bold mb-0">Loan Types</h4>
            <p class="text-muted mb-0">
                Manage the loan products available to borrowers
            </p>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><h4>Form Loan Type</h4></div>
                <form @submit.prevent="loanTypeFormSubmit">
                    <div class="card-body">
                        <input type="hidden" v-model="loanTypesForm.id" />
                        <div class="mb-3">
                            <label class="form-label">Loan Type Name</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="e.g. Salary Loan"
                                v-model="loanTypesForm.name"
                            />
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"
                                    >Interest Rate (%)</label
                                >
                                <div class="input-group">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="form-control"
                                        placeholder="0.00"
                                        v-model="loanTypesForm.interest_rate"
                                    />
                                    <span class="input-group-text"
                                        >% / month</span
                                    >
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Penalty</label>
                                <div class="input-group">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="form-control"
                                        placeholder="0.00"
                                        v-model="loanTypesForm.penalty"
                                    />
                                    <span class="input-group-text"
                                        >% / month
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea
                                class="form-control"
                                rows="6"
                                placeholder="Short description of this loan type..."
                                v-model="loanTypesForm.desc"
                            ></textarea>
                        </div>

                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="loanTypesForm.isActive"
                            />
                            <label class="form-check-label" for="is_active"
                                >Active</label
                            >
                        </div>
                    </div>
                    <div class="card-footer">
                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            @click="clearForm"
                        >
                            Reset Form
                        </button>
                        <button type="submit" class="btn btn-success float-end">
                            {{
                                loanTypesForm.processing
                                    ? "Saving"
                                    : loanTypesFormMode === "create"
                                      ? "Add"
                                      : "Save"
                            }}
                            Loan Type
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Loan Types</h4>
                </div>
                <div class="card-body">
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
                                v-model="searchTypeName"
                            />
                        </div>

                        <select
                            class="form-select"
                            v-model="searchTypeStatus"
                            style="max-width: 160px"
                        >
                            <option value="">All Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Interest Rate</th>
                                    <th>Penalty</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody v-if="filterTypes.length > 0">
                                <tr
                                    v-for="(type, index) in filterTypes"
                                    :key="index"
                                >
                                    <td class="fw-semibold">{{ type.name }}</td>
                                    <td>
                                        <span
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle"
                                            >{{ type.interest_rate }}% /
                                            month</span
                                        >
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle"
                                            >{{ type.penalty }}% of due
                                            amount</span
                                        >
                                    </td>
                                    <td class="title-cell">
                                        <div class="t-truncate">
                                            {{ type.desc }}
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle"
                                            v-if="type.isActive === 1"
                                            >Active</span
                                        >
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle"
                                            v-else-if="type.isActive === 0"
                                            >Disabled</span
                                        >
                                    </td>
                                    <td class="text-end">
                                        <button
                                            class="btn btn-outline-warning btn-sm"
                                            @click="fetchType(type)"
                                        >
                                            <i
                                                class="fa-solid fa-pen-to-square"
                                            ></i>
                                        </button>
                                        <button
                                            class="btn btn-outline-success btn-sm"
                                            @click="loanTypeStatus(type)"
                                            v-if="type.isActive === 1"
                                        >
                                            <i
                                                class="fa-solid fa-circle-plus"
                                            ></i>
                                        </button>
                                        <button
                                            class="btn btn-outline-danger btn-sm"
                                            @click="loanTypeStatus(type)"
                                            v-else-if="type.isActive === 0"
                                        >
                                            <i
                                                class="fa-solid fa-circle-minus"
                                            ></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <h5 class="text-muted mb-0">No Data</h5>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
