<template>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
        <div class="offcanvas-header border-bottom">
            <div class="d-flex align-items-center gap-2">
                <div
                    class="logo-box"
                    style="
                        width: 34px;
                        height: 34px;
                        border-radius: 0.5rem;
                        background: var(--fema-primary);
                        color: #fff;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 700;
                    "
                >
                    F
                </div>
                <div>
                    <div class="fw-semibold small lh-1">FEMA</div>
                    <div class="text-muted lh-1" style="font-size: 0.72rem">
                        Loan Management System
                    </div>
                </div>
            </div>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
            ></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <nav class="nav flex-column p-2">
                <a
                    href="#"
                    class="nav-link active d-flex align-items-center gap-2 px-3 py-2 rounded"
                    ><i class="bi bi-speedometer2"></i> Dashboard</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-file-earmark-text"></i> Loan
                    Applications</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-cash-stack"></i> Active Loans</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-credit-card"></i> Payments</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-people"></i> Borrowers</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-tags"></i> Loan Types</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-bar-chart-line"></i> Reports</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-person-gear"></i> Users</a
                >
                <a
                    href="#"
                    class="nav-link d-flex align-items-center gap-2 px-3 py-2 rounded text-dark"
                    ><i class="bi bi-gear"></i> Settings</a
                >
            </nav>
            <div class="mt-auto border-top p-3">
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
                    <a
                        href="#"
                        class="btn btn-outline-secondary btn-sm flex-fill"
                        >Profile</a
                    >
                    <a href="#" class="btn btn-outline-danger btn-sm flex-fill"
                        >Logout</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>
