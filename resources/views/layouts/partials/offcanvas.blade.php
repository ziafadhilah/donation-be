<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa fa-tachometer-alt me-2"></i>Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}"
                href="{{ route('campaigns.index') }}">
                <i class="fa fa-bullseye me-2"></i>Goals
            </a>
            <a class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}"
                href="{{ route('donations.index') }}">
                <i class="fa fa-hand-holding-dollar me-2"></i>Donations
            </a>
            <a class="nav-link {{ request()->routeIs('fund-realizations.*') ? 'active' : '' }}"
                href="{{ route('fund-realizations.index') }}">
                <i class="fa fa-file-invoice-dollar me-2"></i>Fund Realizations
            </a>
        </nav>
    </div>
</div>
