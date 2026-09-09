<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const formDeptMode = ref("create");
const formDept = useForm({
    name: "",
    acronym: "",
    code: "",
});

const fetchDept = (dept) => {
    formDeptMode.value = "edit";
    formDept.id = dept.id;
    formDept.name = dept.name;
    formDept.code = dept.code;
    formDept.acronym = dept.acronym;
};

const formdeptSubmit = () => {
    if (formDeptMode.value === "create") {
        formDeptMode.value = "create";
        formDept.post(route("dept.store"), {
            onSuccess: () => {
                formDept.reset();
            },
        });
    } else {
        formDeptMode.value = "edit";
        formDept.post(route("dept.edit", formDept.id), {
            onSuccess: () => {
                formDept.reset();
            },
        });
    }
};

const props = defineProps({
    depts: Array,
});
</script>
<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";

export default {
    layout: AdminLayout,
};
</script>
<template>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="font-display mb-0" style="font-size: 2rem">
                Departments Lists
            </h1>
        </div>
    </div>
    <div class="mt-4">
        <div class="row">
            <div class="col-md-4">
                <form @submit.prevent="formdeptSubmit">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="">Department Form</h3>
                            <input type="hidden" v-model="formDept.id" />
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="">Department Acronym:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="formDept.acronym"
                                    />
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label for="">Department Code:</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="formDept.code"
                                    />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="">Department Name:</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    v-model="formDept.name"
                                />
                            </div>
                        </div>
                        <div class="card-footer">
                            <button
                                type="submit"
                                class="btn btn-success px-5 float-end"
                            >
                                {{
                                    formDept.processing
                                        ? "Saving.."
                                        : formDeptMode === "create"
                                          ? "Add"
                                          : "Save Changes"
                                }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Department Lists</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">
                                        Department Details
                                    </th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(dept, index) in depts" :key="index">
                                    <td class="align-middle">
                                        <p>
                                            Name:
                                            <b>{{ dept.name }}</b>
                                        </p>
                                        <p>
                                            Acronym:
                                            <b>{{ dept.acronym }}</b>
                                        </p>
                                        <p>
                                            code: <b>{{ dept.code }}</b>
                                        </p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <button class="btn btn-outline-success">
                                            <i
                                                class="fa-solid fa-file"
                                                @click="fetchDept(dept)"
                                            ></i>
                                        </button>
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
