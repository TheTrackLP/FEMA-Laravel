<script setup>
import { Head } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import { Modal } from "bootstrap";

const accordionOpen = ref(false);

const modalRef = ref(null);
let modalInstance = null;
const openFormModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

const checkBoxShow = ref(false);

const openAccountModal = () => {
    openFormModal();
};
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
                    <th>User Details</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(borrow, index) in 2"
                    :key="index"
                    class="text-center align-middle"
                >
                    <td>{{ index + 1 }}</td>
                    <td class="text-start">
                        <div class="fw-semibold">Juan Dela Cruz</div>
                        <small class="text-muted"
                            >@jdelacruz &middot; juan@example.com</small
                        >
                    </td>
                    <td>Role</td>
                    <td>
                        <span class="badge text-bg-secondary">Pending</span>
                    </td>
                    <td>Date</td>
                    <td class="text-center">
                        <button class="btn btn-outline-warning">
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

    <div class="modal fade" ref="modalRef" tabindex="-1">
        <div class="modal-dialog">
            <form action="">
                <div class="modal-content">
                    <div
                        class="modal-header"
                        style="background: var(--navy); color: #fff"
                    >
                        <h5
                            class="modal-title font-display"
                            style="font-size: 1.05rem"
                        >
                            User Form
                        </h5>
                        <button
                            type="button"
                            class="btn-close btn-close-white"
                        ></button>
                    </div>
                    <input type="hidden" />
                    <div class="modal-body">
                        <div class="form-group mb-3" v-if="checkBoxShow">
                            <label for="">Author</label>
                            <v-select
                                label="fullname"
                                placeholder="Select Author"
                            ></v-select>
                        </div>
                        <div class="form-group mb-3" v-else>
                            <label for="">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="e.g. Juan Dela Cruz"
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
                                Check if Author
                            </label>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Username</label>
                            <input
                                type="text"
                                class="form-control"
                                placeholder="e.g. JuanDelaCruz223"
                            />
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                placeholder="e.g. juandelacruz@example.com"
                            />
                        </div>
                        <div class="form-group mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">Role</label>
                                    <select class="form-control">
                                        <option value="" selected>
                                            Select Role
                                        </option>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Status</label>
                                    <select class="form-control">
                                        <option value="" selected>
                                            Select Status
                                        </option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 form-group mb-3">
                                <label for="">Password</label>
                                <input type="password" class="form-control" />
                            </div>
                            <div class="col-6 form-group mb-3">
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success px-4">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
