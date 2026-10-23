<script setup lang="ts">
import Navbar from "@/Components/Navbar.vue";
import Notification from "@/Components/Notification.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { ref } from "vue";

const sidebarOpen = ref(false); // mobile: slide-in
const collapsed = ref(false); // desktop: icon-only
</script>

<template>
    <Notification />
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
