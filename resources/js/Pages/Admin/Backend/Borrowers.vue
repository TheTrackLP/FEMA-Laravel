<script setup>
import { nextTick, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat } from "@/GlobalReuse/currencyFormat";

const statushide = ref(false);

const modalRef = ref(null);
let modalInstance = null;

const openModal = () => {
    nextTick(() => {
        modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

const openBorrowerForm = () => {
    openModal();
    statushide.value = false;
};

const closeModal = () => {
    modalInstance?.hide();
};

const borrowerFormMode = ref("create");
const borrowerForm = useForm({
    fullname: "",
    datebirth: "",
    contact: "",
    address: "",
    empid: "",
    sharedcapital: "",
    deptid: "",
    yearservice: "",
    status: "",
});

const fetchBorrower = () => {};

const borrowerFormSubmit = () => {
    if (borrowerFormMode.value === "create") {
        borrowerFormMode.value = "create";
        borrowerForm.post(route("borrow.store"), {
            onSuccess: () => {
                borrowerForm.reset();
                closeModal();
            },
        });
    } else {
        borrowerFormMode.value = "edit";
        borrowerForm.post(route("borrow.update", borrowerForm.id), {
            onSuccess: () => {
                borrowerForm.reset();
                closeModal();
            },
        });
    }
};

const props = defineProps({
    depts: Array,
    borrows: Array,
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
    border: 1px solid gray;
}
</style>
<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="font-display mb-0" style="font-size: 2rem">
                Borrowers Lists
            </h1>
        </div>
        <button class="btn btn-primary" @click="openBorrowerForm">
            <i class="fa-solid fa-plus"></i> New Borrower
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-info">
                <tr>
                    <th>#</th>
                    <th>Employee ID</th>
                    <th>Borrower's Name</th>
                    <th>Shared Capital</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(borrow, index) in borrows" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ borrow.empid }}</td>
                    <td>{{ borrow.fullname }}</td>
                    <td>{{ currencyFormat(borrow.sharedcapital) }}</td>
                    <td>{{ borrow.name }}</td>
                    <td>
                        <span
                            class="badge text-bg-danger"
                            v-if="borrow.status === 0"
                            >New Applicant</span
                        >
                        <span
                            class="badge text-bg-success"
                            v-else-if="borrow.status === 1"
                            >Exist Applicant</span
                        >
                    </td>
                    <td>
                        {{ borrow.datebirth }}
                    </td>
                    <td>Action</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="modal fade" ref="modalRef">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Mew Borrower</h3>
                </div>
                <form @submit.prevent="borrowerFormSubmit">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 for="">Personal Information</h4>
                                <input
                                    type="hidden"
                                    v-model="borrowerForm.id"
                                />
                                <hr />
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="">First Name:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter First Name"
                                            v-model="borrowerForm.fullname"
                                        />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Date of Birth:</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            v-model="borrowerForm.datebirth"
                                        />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Contact #:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Contact #"
                                            v-model="borrowerForm.contact"
                                        />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Description #:</label>
                                        <textarea
                                            class="form-control"
                                            cols="7"
                                            rows="7"
                                            v-model="borrowerForm.address"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 for="">Profile Settings</h4>
                                <hr />
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Employee ID:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Employee ID"
                                            v-model="borrowerForm.empid"
                                        />
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Shared Capital:</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Enter Shared Capital"
                                            v-model="borrowerForm.sharedcapital"
                                        />
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Department:</label>
                                        <v-select
                                            :options="depts"
                                            :reduce="(dept) => dept.id"
                                            label="name"
                                            placeholder="Select Department"
                                            v-model="borrowerForm.deptid"
                                        ></v-select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Years in Service:</label>
                                        <select
                                            class="form-select"
                                            v-model="borrowerForm.yearservice"
                                        >
                                            <option value="">
                                                Select Year oF Service
                                            </option>
                                            <option value="1">
                                                1 to 4 Years
                                            </option>
                                            <option value="2">
                                                5 to 9 Years
                                            </option>
                                            <option value="3">
                                                10 Above Years
                                            </option>
                                        </select>
                                    </div>
                                    <div
                                        class="col-md-6 mb-3"
                                        v-if="statushide"
                                    >
                                        <label for="">Status:</label>
                                        <select
                                            class="form-select"
                                            v-model="borrowerForm.status"
                                        >
                                            <option value="">
                                                Select Status
                                            </option>
                                            <option value="0">
                                                New Applicant
                                            </option>
                                            <option value="1">
                                                Existing Applicant
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <hr />
                                <div class="row">
                                    <!-- <div class="col-md-6 mb-3">
                                            <label for="">Username:</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Enter Username"
                                                v-model=""
                                            />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="">Email:</label>
                                            <input
                                                type="email"
                                                class="form-control"
                                                placeholder="Enter Email"
                                            />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="">Password:</label>
                                            <input
                                                type="password"
                                                class="form-control"
                                                placeholder="Enter Password"
                                            />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="">Confirm Password:</label>
                                            <input
                                                type="password"
                                                class="form-control"
                                                placeholder="Confirmed Password"
                                            />
                                        </div> -->
                                    <div class="col-md-12 mt-5">
                                        <button
                                            type="button"
                                            class="btn btn-danger px-5"
                                            @click="closeModal"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="btn btn-success px-5 float-end"
                                        >
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
