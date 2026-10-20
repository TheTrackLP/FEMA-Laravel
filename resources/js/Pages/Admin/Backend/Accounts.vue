<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import { Modal } from "bootstrap";
import { formatDate } from "@/reuseables";

const accordionOpen = ref(false);
const checkboxPassword = ref(false);

const modalRef = ref(null);
let modalInstance = null;
const openFormModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

const closeModal = () => {
    modalInstance?.hide();
    accountsForm.reset();
};

const checkBoxShow = ref(false);

const accountsFormMode = ref("create");

const accountsForm = useForm({
    borrower_id: "",
    name: "",
    username: "",
    email: "",
    roles: "",
    status: "",
    password: "",
    confirmPass: "",
});

const submitAccountForm = () => {
    if (accountsFormMode.value === "create") {
        accountsFormMode.value = "create";
        accountsForm.post(route("accts.create"), {
            onSuccess: () => {
                accountsForm.reset();
                closeModal();
            },
        });
    } else {
        accountsFormMode.value = "edit";
        accountsForm.post(route("accts.update", accountsForm.id), {
            onSuccess: () => {
                accountsForm.reset();
                closeModal();
            },
        });
    }
};

const openAccountModal = () => {
    openFormModal();
    accountsFormMode.value = "create";
};

const fetchAccountData = (acct) => {
    accountsFormMode.value = "edit";
    openFormModal();
    accountsForm.id = acct.id;
    accountsForm.borrower_id = acct.borrower_id;
    accountsForm.name = acct.name;
    accountsForm.username = acct.username;
    accountsForm.email = acct.email;
    accountsForm.roles = acct.roles;
    accountsForm.status = acct.status;
};

const props = defineProps({
    accounts: Array,
    borrowers: Array,
});
</script>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>

<template>
    <Head title="Accounts" />
    <div
        class="d-flex justify-content-between align-items-center page-header flex-wrap gap-2 mb-3"
    >
        <div>
            <h4 class="fw-bold mb-0">Accounts</h4>
            <p class="text-muted mb-0">
                Manage both Borrowers and Staffs Accounts
            </p>
        </div>
        <button class="btn btn-primary" @click="openAccountModal">
            <i class="fa-solid fa-plus"></i> New Account
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
                            />
                        </div>
                        <div class="col-lg-4">
                            <label for="">Department</label>
                        </div>
                        <div class="col-lg-4">
                            <label for="">Status</label>
                            <select
                                v-model="selectedStatus"
                                class="form-select"
                            >
                                <option value="">Select Status</option>
                                <option :value="1">Active</option>
                                <option :value="0">Inactive</option>
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
                    <th>User Details</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(acct, index) in accounts"
                    :key="index"
                    class="text-center align-middle"
                >
                    <td>{{ index + 1 }}</td>
                    <td class="text-start">
                        <div class="fw-semibold" v-if="acct.borrower_id">
                            {{ acct.fullname }}
                        </div>
                        <div class="fw-semibold" v-else>{{ acct.name }}</div>
                        <small class="text-muted"
                            >@{{ acct.username }} &middot;
                            {{ acct.email }}</small
                        >
                    </td>
                    <td>
                        <span
                            class="badge text-bg-primary"
                            v-if="acct.roles == 'admin'"
                            >Admin</span
                        >
                    </td>
                    <td>
                        <span
                            class="badge text-bg-success"
                            v-if="acct.status === 1"
                            >Active</span
                        >
                        <span
                            class="badge text-bg-danger"
                            v-else-if="acct.status === 0"
                            >Inactive</span
                        >
                    </td>
                    <td>
                        <p v-if="acct.datejoined">
                            {{ formatDate(acct.datejoined) }}
                        </p>
                    </td>
                    <td class="text-center">
                        <button
                            class="btn btn-outline-warning"
                            @click="fetchAccountData(acct)"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
            <!-- <tbody v-else>
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <h5 class="text-muted mb-0">No Data</h5>
                    </td>
                </tr>
            </tbody> -->
        </table>
    </div>

    <div
        class="modal fade"
        ref="modalRef"
        tabindex="-1"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
    >
        <div class="modal-dialog">
            <form @submit.prevent="submitAccountForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title font-display">Accounts Form</h4>
                        <button
                            type="button"
                            class="btn-close btn-close"
                            @click="closeModal"
                        ></button>
                    </div>
                    <input type="hidden" v-model="accountsForm.id" />
                    <div class="modal-body">
                        <div class="form-group mb-3" v-if="checkBoxShow">
                            <label for="">Borrower</label>
                            <v-select
                                :options="borrowers"
                                :reduce="(borrow) => borrow.id"
                                label="empid"
                                placeholder="Select Borrower"
                                v-model="accountsForm.borrower_id"
                            >
                                <template #option="borrow">
                                    {{ borrow.fullname }} | {{ borrow.empid }}
                                </template>
                                <template #selected-option="borrow">
                                    {{ borrow.fullname }} | {{ borrow.empid }}
                                </template>
                            </v-select>
                        </div>
                        <div class="form-group mb-3" v-else>
                            <label for="">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="e.g. Juan Dela Cruz"
                                v-model="accountsForm.name"
                            />
                        </div>
                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="checkBoxShow"
                                id="checkDefault"
                            />
                            <label class="form-check-label" for="checkDefault">
                                Check if Borrower
                            </label>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Username</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="e.g. JuanDelaCruz223"
                                v-model="accountsForm.username"
                            />
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                placeholder="e.g. juandelacruz@example.com"
                                v-model="accountsForm.email"
                            />
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Role</label>
                                    <select
                                        class="form-control"
                                        v-model="accountsForm.roles"
                                    >
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="borrower">
                                            Borrower
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Status</label>
                                    <select
                                        class="form-control"
                                        v-model="accountsForm.status"
                                    >
                                        <option value="" selected>
                                            Select Status
                                        </option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                v-model="checkboxPassword"
                                id="checkPassword"
                            />
                            <label class="form-check-label" for="checkPassword">
                                Check if Change Password
                            </label>
                        </div>
                        <div class="row" v-if="checkboxPassword">
                            <div class="col-6 form-group mb-3">
                                <label for="">Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="accountsForm.password"
                                />
                            </div>
                            <div class="col-6 form-group mb-3">
                                <label for="">Confirm Password</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    v-model="accountsForm.confirmPass"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success px-4">
                            {{
                                accountsForm.processing
                                    ? "Saving..."
                                    : accountsFormMode === "create"
                                      ? "Add"
                                      : "Save"
                            }}
                            Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
