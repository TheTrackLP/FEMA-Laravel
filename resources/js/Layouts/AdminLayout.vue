<script setup lang="ts">
import Navbar from "@/Components/Navbar.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { watch, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";

const sidebarOpen = ref(false); // mobile: slide-in
const collapsed = ref(false); // desktop: icon-only

function toggleSidebar() {
    if (window.matchMedia("(min-width: 992px)").matches) {
        collapsed.value = !collapsed.value;
    } else {
        sidebarOpen.value = !sidebarOpen.value;
    }
}

// close the mobile sidebar after navigating
const page = usePage();
watch(
    () => page.url,
    () => (sidebarOpen.value = false),
);

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
    <body>
        <!-- ===================== Sidebar ===================== -->
        <aside
            class="offcanvas offcanvas-start sidebar"
            :class="{ show: sidebarOpen, collapsed }"
            data-bs-theme="dark"
        >
            <Sidebar :collapsed="collapsed" />
        </aside>
        <div v-if="sidebarOpen" @click="sidebarOpen = false"></div>

        <!-- ===================== Main Application ===================== -->
        <div
            id="main-content"
            class="main-content d-flex flex-column min-vh-100"
        >
            <Navbar
                :collapsed="collapsed"
                @toggle-sidebar="collapsed = !collapsed"
            />

            <!-- Page Content -->
            <main class="container-fluid p-3 p-md-4 flex-grow-1">
                <slot />
            </main>
        </div>
    </body>
</template>
