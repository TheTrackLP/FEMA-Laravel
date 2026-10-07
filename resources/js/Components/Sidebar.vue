<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const isActive = (routeName) => {
    return page.url && route().current(routeName);
};
</script>
<style>
img {
    display: block;
    max-width: 140px;
    max-height: 150px;
    width: auto;
    height: auto;
    margin-left: auto;
    margin-right: auto;
    border-radius: 50px;
}
</style>
<template>
    <!-- Brand -->
    <div class="offcanvas-header">
        <a
            href="#"
            class="d-flex align-items-center gap-2 text-white text-decoration-none fw-semibold fs-5"
            title="My Admin System"
        >
            <i class="fa-solid fa-school"></i>
            <span class="nav-text">Loan System</span>
        </a>
        <button
            type="button"
            class="btn-close d-lg-none"
            data-bs-dismiss="offcanvas"
            aria-label="Close menu"
        ></button>
    </div>

    <div class="offcanvas-body d-flex flex-column px-2 py-3">
        <img class="rounded-circle py-1 mb-3" src="/img/femalogo.png" />

        <!-- Navigation -->
        <nav aria-label="Main menu">
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item">
                    <Link
                        class="nav-link d-flex align-items-center gap-2"
                        :href="route('admin.dash')"
                        :class="{ active: isActive('admin.*') }"
                        title="Dashboard"
                    >
                        <i class="fa-solid fa-house"></i
                        ><span class="nav-text">Home</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <Link
                        class="nav-link d-flex align-items-center gap-2"
                        :href="route('borrow.dash')"
                        :class="{ active: isActive('borrow.*') }"
                        title="Users"
                    >
                        <i class="fa-solid fa-people-group"></i
                        ><span class="nav-text">Borrowers</span>
                    </Link>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-2"
                        href="#"
                        title="Employees"
                    >
                        <i class="fa-solid fa-lines-leaning"></i
                        ><span class="nav-text">Loans</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-2"
                        href="#"
                        title="Departments"
                    >
                        <i class="fa-solid fa-money-bill"></i
                        ><span class="nav-text">Payments</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-2"
                        href="#"
                        title="Reports"
                    >
                        <i class="fa-solid fa-boxes-stacked"></i
                        ><span class="nav-text">Loan Types</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-2"
                        href="#"
                        title="Settings"
                    >
                        <i class="fa-solid fa-chart-simple"></i
                        ><span class="nav-text">Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-2"
                        href="#"
                        title="Settings"
                    >
                        <i class="fa-solid fa-users"></i
                        ><span class="nav-text">Staff/Borrowers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link d-flex align-items-center gap-2"
                        href="#"
                        title="Settings"
                    >
                        <i class="fa-solid fa-building"></i
                        ><span class="nav-text">Departments</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Logout -->
        <div class="sidebar-footer mt-auto pt-3">
            <a
                class="nav-link d-flex align-items-center gap-2"
                href="#"
                title="Logout"
            >
                <i class="fa-solid fa-power-off text-danger"></i
                ><span class="nav-text">Logout </span>
            </a>
        </div>
    </div>
</template>
