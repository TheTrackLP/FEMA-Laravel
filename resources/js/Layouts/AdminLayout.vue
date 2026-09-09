<script setup lang="ts">
import Navbar from "@/Components/Navbar.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const page = usePage();

watch(
    () => page.props.flash?.success,
    (value) => {
        if (value) {
            Swal.fire({
                title: "Success!",
                text: value,
                icon: "success",
                timer: 1500,
                showConfirmButton: false,
            });
        }
    },
    { deep: true },
);

watch(
    () => page.props.flash?.error,
    (value) => {
        if (value) {
            Swal.fire({
                title: "Error!",
                text: value,
                icon: "error",
                timer: 1500,
                showConfirmButton: false,
            });
        }
    },
    { deep: true },
);
</script>

<template>
    <Sidebar />

    <!-- Offcanvas Sidebar (mobile) -->
    <Navbar />

    <div class="main-wrapper">
        <!-- Top navbar -->
        <nav class="navbar topbar sticky-top px-3 px-lg-4">
            <div class="d-flex align-items-center gap-3 w-100">
                <button
                    class="btn btn-outline-secondary sidebar-mobile-toggle"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarOffcanvas"
                >
                    <i class="bi bi-list"></i>
                </button>

                <h1 class="h6 mb-0 d-none d-sm-block">Dashboard</h1>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <div class="d-none d-md-block">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"
                                ><i class="bi bi-search text-muted"></i
                            ></span>
                            <input
                                type="text"
                                class="form-control border-start-0"
                                placeholder="Search..."
                            />
                        </div>
                    </div>

                    <button class="btn btn-light position-relative">
                        <i class="bi bi-bell"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="font-size: 0.6rem"
                            >3</span
                        >
                    </button>

                    <div class="dropdown">
                        <a
                            href="#"
                            class="d-flex align-items-center gap-2 text-decoration-none text-dark dropdown-toggle"
                            data-bs-toggle="dropdown"
                        >
                            <img
                                src="https://ui-avatars.com/api/?name=Maria+Santos&background=2c5cc5&color=fff"
                                class="rounded-circle"
                                width="32"
                                height="32"
                                alt="avatar"
                            />
                            <span class="d-none d-md-inline small fw-medium"
                                >Maria Santos</span
                            >
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#"
                                    ><i class="bi bi-person me-2"></i>Profile</a
                                >
                            </li>
                            <li>
                                <a class="dropdown-item" href="#"
                                    ><i class="bi bi-gear me-2"></i>Settings</a
                                >
                            </li>
                            <li><hr class="dropdown-divider" /></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                    ><i class="bi bi-box-arrow-right me-2"></i
                                    >Logout</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main class="p-3 p-lg-4">
            <slot />
        </main>
    </div>
</template>
