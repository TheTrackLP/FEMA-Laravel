<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>
<style>
body {
    background-color: #f8f9fa;
}
.login-card {
    max-width: 420px;
    width: 100%;
}
</style>
<template>
    <Head title="Login" />
    <div
        class="min-vh-100 d-flex align-items-center justify-content-center px-3 py-5"
    >
        <div class="login-card">
            <div class="text-center mb-4">
                <img
                    src="/img/femalogo.png"
                    class="mx-auto"
                    width="200"
                    height="200"
                    alt=""
                />
                <h1 class="fw-bold mb-0">FEMA</h1>
                <p class="text-muted small mb-0">Loan Management System</p>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-sm-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold h4 mb-1">Welcome Back</h2>
                        <p class="text-muted small mb-0">
                            Sign in to access the FEMA Loan Management System.
                        </p>
                    </div>
                    <form @submit.prevent="submit">
                        <div class="mb-3">
                            <label for="username" class="form-label"
                                >Username or Email</label
                            >
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fa-solid fa-user"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Enter your username or email"
                                    required
                                    v-model="form.email"
                                />
                                <div class="invalid-feedback">
                                    Please enter your username or email.
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label"
                                >Password</label
                            >
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input
                                    type="password"
                                    class="form-control"
                                    placeholder="Enter your password"
                                    required
                                    v-model="form.password"
                                />
                                <div class="invalid-feedback">
                                    Please enter your password.
                                </div>
                            </div>
                        </div>
                        <div
                            class="d-flex justify-content-between align-items-center mb-4"
                        >
                            <a href="#" class="small text-decoration-none"
                                >Forgot Password?</a
                            >
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <span id="submitText">Sign In</span>
                            <span
                                id="submitSpinner"
                                class="spinner-border spinner-border-sm ms-2 d-none"
                                role="status"
                                aria-hidden="true"
                            ></span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4">
                <small class="text-muted"
                    >© 2026 FEMA Loan Management System</small
                >
            </div>
        </div>
    </div>
</template>
