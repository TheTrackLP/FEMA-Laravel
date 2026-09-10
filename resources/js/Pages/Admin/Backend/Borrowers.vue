<script setup>
import { nextTick, ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { Modal } from "bootstrap";
import { currencyFormat, formatDate } from "@/reuseables";

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

const fetchBorrower = (borrow) => {
    openModal();
    borrowerFormMode.value = "edit";
    statushide.value = true;
    borrowerForm.id = borrow.id;
    borrowerForm.fullname = borrow.fullname;
    borrowerForm.datebirth = borrow.datebirth;
    borrowerForm.contact = borrow.contact;
    borrowerForm.address = borrow.address;
    borrowerForm.empid = borrow.empid;
    borrowerForm.sharedcapital = borrow.sharedcapital;
    borrowerForm.deptid = borrow.deptid;
    borrowerForm.yearservice = borrow.yearservice;
    borrowerForm.status = borrow.status;
};

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

const changeBorrowerStatus = (borrow) => {
    borrowerForm.post(route("borrow.status", borrow.id));
};

const accordionOpen = ref(false);

const selectedName = ref("");
const selectedDept = ref("");
const selectedStatus = ref("");

const filteredLoans = computed(() => {
    const filterName = selectedName.value.toLowerCase().trim();
    const filterDept = selectedDept.value;
    const filterStatus = selectedStatus.value;

    return props.borrows.filter((borrow) => {
        const matchName =
            !filterName || borrow.fullname.toLowerCase().includes(filterName);
        const matchDept = !filterDept || borrow.deptid === filterDept;
        const matchStatus =
            filterStatus === "" ||
            Number(borrow.status) === Number(filterStatus);
        return matchName && matchDept && matchStatus;
    });
});

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
    overflow: auto;
    max-height: 730px;
}
.table-responsive th {
    position: sticky;
    top: 0;
    z-index: 2;
}
</style>
<template>
    <Head title="Borrowers" />
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
                    <th>Employee ID</th>
                    <th>Borrower's Name</th>
                    <th>Shared Capital</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Date Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="filteredLoans.length > 0">
                <tr
                    v-for="(borrow, index) in filteredLoans"
                    :key="index"
                    class="text-center align-middle"
                >
                    <td>{{ index + 1 }}</td>
                    <td>{{ borrow.empid }}</td>
                    <td class="text-right">{{ borrow.fullname }}</td>
                    <td>{{ currencyFormat(borrow.sharedcapital) }}</td>
                    <td>{{ borrow.name }}</td>
                    <td>
                        <span
                            class="badge text-bg-secondary"
                            v-if="borrow.status === 0"
                            >Pending</span
                        >
                        <span
                            class="badge text-bg-success"
                            v-else-if="borrow.status === 1"
                            >Active</span
                        >
                        <span
                            class="badge text-bg-danger"
                            v-else-if="borrow.status === 2"
                            >Disabled</span
                        >
                    </td>
                    <td>
                        {{ formatDate(borrow.datejoined) }}
                    </td>
                    <td class="text-center">
                        <button
                            class="btn btn-outline-warning"
                            @click="fetchBorrower(borrow)"
                        >
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button
                            class="btn btn-outline-success"
                            @click="changeBorrowerStatus(borrow)"
                            v-if="borrow.status === 1"
                        >
                            <i class="fa-solid fa-circle-plus"></i>
                        </button>
                        <button
                            class="btn btn-outline-danger"
                            @click="changeBorrowerStatus(borrow)"
                            v-else-if="borrow.status === 2"
                        >
                            <i class="fa-solid fa-circle-minus"></i>
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
    <div
        class="modal fade"
        ref="modalRef"
        data-bs-keyboard="false"
        data-bs-backdrop="static"
    >
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
                                            <option value="0">Pending</option>
                                            <option value="1">Active</option>
                                            <option value="2">Disabled</option>
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
