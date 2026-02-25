<nav class="navbar topbar navbar-expand-lg bg-white py-2 shadow-sm">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasSidebar">
                <i class="fa fa-bars"></i>
            </button>

            <a class="navbar-brand brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <span class="badge bg-primary rounded-pill">ECC</span>
                <span class="ms-1">ECC Church</span>
            </a>
        </div>
        {{-- <form class="d-none d-md-flex ms-3 row-1">
                <div class="input-group search-input">
                    <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                    <input class="form-control border-start-0" type="search"
                        placeholder="Search members, events, donations...">
                </div>
            </form> --}}

        <div class="d-flex align-items-center gap-3">
            <button id="themeToggle" class="btn btn-sm btn-outline-secondary d-none d-md-inline" title="Toggle theme"><i
                    class="fa fa-sun"></i></button>

            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button"
                    id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://i.pravatar.cc/64?img=12" alt="avatar" class="user-avatar me-2">
                    <div class="d-none d-sm-block text-start">
                        <div style="font-size:.9rem">John Doe</div>
                        <div style="font-size:.75rem;color:var(--muted)">Admin</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa fa-cog me-2"></i>Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="#"><i
                                class="fa fa-sign-out-alt me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
