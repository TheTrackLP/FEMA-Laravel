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
    .nav-link{
        background-color: white !important;
        border-radius: 50px;
        font-weight: bold;
        color: black;
    }
    .button-hover:hover,
    .button-hover.active {
        background-color: #0081A7 !important;
        color: white !important;
        border-radius: 50px;
        font-weight: bold;
    }
    
    hr {
        margin: 5px;
    }
    
    .side_foot {
        background-color: #fff !important;
        color: black;
    }
    </style>
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion"
            style="background-image: linear-gradient(to bottom right, #00AFB9, #FED9B7)">
            <div class="sb-sidenav-menu">
                <img class="rounded-circle py-1 mb-3" src="{{ asset('assets/img/filamer.png') }}">
                <div class="nav">
                    <a class="nav-link button-hover" href="">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-house"></i></div>
                        Home
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{ route('borrow.list') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                        Borrowers
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{ route('loans.dash') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Loan
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{ route('pay.list') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                        Payments
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{ route('plan.list') }}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                        Loan Plans
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{ route('report')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-book-open"></i></div>
                        Reports
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{route('accts')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                        Staffs/Borrowers
                    </a>
                    <hr>
                    <a class="nav-link button-hover" href="{{route('dept.list')}}">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-building"></i></div>
                        Departments
                    </a>
                </div>
            </div>
            <div class="sb-sidenav-footer side_foot">
                <div class="small">Logged in as:
                    <a href="{{route('admin.logout')}}" class="btn btn-danger float-end"><i
                            class="fa-solid fa-power-off"></i></a>
                </div>
                Start Bootstrap
            </div>
        </nav>
    </div>