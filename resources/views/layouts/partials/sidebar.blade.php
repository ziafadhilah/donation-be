<aside class="app-sidebar d-none d-lg-block">
    <div class="sidebar-header text-white">
        <h5 class="mb-0 ps-3">ECC Dashboard</h5>
    </div>
    <nav class="nav flex-column px-2">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="fa fa-tachometer-alt"></i>Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('campaigns.*') ? 'active' : '' }}" href="{{ route('campaigns.index') }}">
            <i class="fa fa-bullseye"></i>Goals
        </a>
        <a class="nav-link {{ request()->routeIs('donations.*') ? 'active' : '' }}"
            href="{{ route('donations.index') }}">
            <i class="fa fa-hand-holding-dollar"></i>Donations
        </a>
        <a class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}" href="{{ route('units.index') }}">
            <i class="fa fa-building"></i>Units
        </a>
        <a class="nav-link {{ request()->routeIs('fund-realizations.*') ? 'active' : '' }}"
            href="{{ route('fund-realizations.index') }}">
            <i class="fa fa-file-invoice-dollar"></i>Fund Realizations
        </a>
    </nav>
</aside>
