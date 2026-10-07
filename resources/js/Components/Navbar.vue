<script setup lang="ts">
const emit = defineEmits(["toggleSidebar"]);
</script>

<template>
    <nav
        class="navbar navbar-expand app-navbar sticky-top px-3"
        data-bs-theme="dark"
        aria-label="Top navigation"
    >
        <!-- Mobile: opens offcanvas -->
        <button
            class="btn btn-outline-light btn-sm d-lg-none me-3"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#sidebar"
            aria-controls="sidebar"
            aria-label="Open menu"
        >
            <i class="fa-solid fa-bars"></i>
        </button>
        <!-- Desktop: expand / collapse -->
        <button
            class="btn btn-outline-light btn-sm me-3"
            type="button"
            aria-label="Toggle sidebar"
            @click.prevent="emit('toggleSidebar')"
        >
            <i class="fa-solid fa-bars"></i>
        </button>

        <!-- Page title -->
        <span class="navbar-text text-white fw-semibold fs-6 py-0"
            >FEMA - FCU Employee Mutual Association</span
        >
    </nav>
</template>
