<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo-box">F</div>
            <div>
                <div class="fw-semibold small lh-1">FEMA</div>
                <div class="text-muted lh-1" style="font-size: 0.72rem">
                    Loan Management System
                </div>
            </div>
        </div>

        <nav class="sidebar-nav nav flex-column">
            <a href="#" class="nav-link active"
                ><i class="fa-solid fa-house"></i> Dashboard</a
            >
            <Link :href="route('borrow.dash')" class="nav-link"
                ><i class="bi bi-file-earmark-text"></i> Borrowers</Link
            >
            <a href="#" class="nav-link"
                ><i class="bi bi-cash-stack"></i> Loans</a
            >
            <a href="#" class="nav-link"
                ><i class="bi bi-credit-card"></i> Payments</a
            >
            <a href="#" class="nav-link"
                ><i class="bi bi-tags"></i> Loan Types</a
            >
            <a href="#" class="nav-link"
                ><i class="bi bi-bar-chart-line"></i> Reports</a
            >
            <a href="#" class="nav-link"
                ><i class="fa-solid fa-users-rectangle"></i> Users</a
            >
            <Link :href="route('dept.dash')" class="nav-link"
                ><i class="bi bi-gear"></i> Departments</Link
            >
        </nav>

        <div class="sidebar-footer">
            <div class="d-flex align-items-center gap-2 mb-2">
                <img
                    src="https://ui-avatars.com/api/?name=Maria+Santos&background=2c5cc5&color=fff"
                    class="rounded-circle"
                    width="36"
                    height="36"
                    alt="avatar"
                />
                <div class="small">
                    <div class="fw-semibold">Maria Santos</div>
                    <div class="text-muted" style="font-size: 0.72rem">
                        Administrator
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="#" class="btn btn-outline-secondary btn-sm flex-fill"
                    >Profile</a
                >
                <a href="#" class="btn btn-outline-danger btn-sm flex-fill"
                    >Logout</a
                >
            </div>
        </div>
    </aside>
</template>
