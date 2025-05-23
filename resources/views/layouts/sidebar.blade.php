<div class="sidebar">
  <div class="sidebar-header p-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center" style="font-size: 12px; margin-top: -10px;">
      <img src="{{ asset('images/newstruk.png') }}" alt="Logo" style="width: 50px; height: 50px;">
      <span class="ms-2 fw-bold d-none d-md-inline">TB. SOGOL ANUGRAH MANDIRI</span>
      <hr>
    </div>
    <button class="btn btn-sm btn-close-white d-lg-none" id="sidebarClose">
      <i class="fas fa-times"></i>
    </button>
  </div>
  <div class="sidebar-divider"></div>
  <div class="sidebar-menu p-1">
    <!-- Dashboard -->
    <div class="sidebar-item">
      <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="sidebar-icon"><i class="fas fa-tachometer-alt"></i></span>
        <span class="sidebar-text">Dashboard</span>
      </a>
    </div>

    <!-- Inventory -->
    <div class="sidebar-item">
      <a href="#inventoryMenu" class="sidebar-link {{ request()->is('inventory*') ? '' : 'collapsed' }}"
        data-bs-toggle="collapse">
        <span class="sidebar-icon"><i class="fas fa-boxes"></i></span>
        <span class="sidebar-text">Inventory</span>
        <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
      </a>
      <div class="collapse {{ request()->is('inventory*') ? 'show' : '' }}" id="inventoryMenu">
        <div class="sidebar-submenu">
          <a href="{{ route('inventory.items') }}"
            class="sidebar-link {{ request()->routeIs('inventory.items') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-list"></i></span>
            <span class="sidebar-text">Daftar Barang</span>
          </a>
          <a href="{{ route('inventory.categories') }}"
            class="sidebar-link {{ request()->routeIs('inventory.categories') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-tags"></i></span>
            <span class="sidebar-text">Jenis Barang</span>
          </a>
          {{-- <a href="{{ route('inventory.stock_opname.index') }}"
            class="sidebar-link {{ request()->routeIs('inventory.stock_opname.*') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-exchange-alt"></i></span>
            <span class="sidebar-text">Stok Opname</span>
          </a>
          <a href="{{ route('inventory.reports.current_stock') }}"
            class="sidebar-link {{ request()->routeIs('inventory.reports.current_stock') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-chart-line"></i></span>
            <span class="sidebar-text">Laporan Stok</span>
          </a> --}}
        </div>
      </div>
    </div>

    <!-- Penjualan -->
    <div class="sidebar-item">
      <a href="#salesMenu" class="sidebar-link {{ request()->is('penjualan*') ? '' : 'collapsed' }}"
        data-bs-toggle="collapse">
        <span class="sidebar-icon"><i class="fas fa-cash-register"></i></span>
        <span class="sidebar-text">Penjualan</span>
        <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
      </a>
      <div class="collapse {{ request()->is('penjualan*') ? 'show' : '' }}" id="salesMenu">
        <div class="sidebar-submenu">
          <a href="{{ route('penjualan.transaksi.create') }}"
            class="sidebar-link {{ request()->routeIs('penjualan.transaksi.create') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-shopping-cart"></i></span>
            <span class="sidebar-text">Transaksi Baru</span>
          </a>
          <a href="{{ route('penjualan.transaksi.index') }}"
            class="sidebar-link {{ request()->routeIs('penjualan.transaksi.index') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-list"></i></span>
            <span class="sidebar-text">Daftar Transaksi</span>
          </a>
          <a href="{{ route('penjualan.retur.index') }}"
            class="sidebar-link {{ request()->routeIs('penjualan.retur.*') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-undo"></i></span>
            <span class="sidebar-text">Retur Penjualan</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Pembelian -->
    <div class="sidebar-item">
      <a href="#purchaseMenu" class="sidebar-link {{ request()->is('pembelian*') ? '' : 'collapsed' }}"
        data-bs-toggle="collapse">
        <span class="sidebar-icon"><i class="fas fa-truck"></i></span>
        <span class="sidebar-text">Pembelian</span>
        <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
      </a>
      <div class="collapse {{ request()->is('pembelian*', 'retur-pembelian*') ? 'show' : '' }}" id="purchaseMenu">
        <div class="sidebar-submenu">
          <a href="{{ route('pembelian.create') }}"
            class="sidebar-link {{ request()->routeIs('pembelian.create') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-cart-plus"></i></span>
            <span class="sidebar-text">Pembelian Baru</span>
          </a>
          <a href="{{ route('pembelian.index') }}"
            class="sidebar-link {{ request()->routeIs('pembelian.index') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-list"></i></span>
            <span class="sidebar-text">Daftar Pembelian</span>
          </a>
          <a href="{{ route('retur-pembelian.index') }}"
            class="sidebar-link {{ request()->routeIs('retur-pembelian.*') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-exchange-alt"></i></span>
            <span class="sidebar-text">Retur Pembelian</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Laporan -->
    <div class="sidebar-item">
      <a href="#reportsMenu" class="sidebar-link {{ request()->is('reports*') ? '' : 'collapsed' }}"
        data-bs-toggle="collapse">
        <span class="sidebar-icon"><i class="fas fa-file-alt"></i></span>
        <span class="sidebar-text">Laporan</span>
        <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
      </a>
      <div class="collapse {{ request()->is('reports*') ? 'show' : '' }}" id="reportsMenu">
        <div class="sidebar-submenu">
          <a href="{{ route('reports.daily-sales') }}"
            class="sidebar-link {{ request()->routeIs('reports.daily-sales') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-chart-bar"></i></span>
            <span class="sidebar-text">Penjualan Harian</span>
          </a>
          <a href="{{ route('reports.monthly-sales') }}"
            class="sidebar-link {{ request()->routeIs('reports.monthly-sales') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-chart-pie"></i></span>
            <span class="sidebar-text">Penjualan Bulanan</span>
          </a>
          <a href="{{ route('reports.profit-loss') }}"
            class="sidebar-link {{ request()->routeIs('reports.profit-loss') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-file-invoice-dollar"></i></span>
            <span class="sidebar-text">Laba Rugi</span>
          </a>
        </div>
      </div>
    </div>

    <!-- Pengaturan -->
    <div class="sidebar-item">
      <a href="#settingsMenu" class="sidebar-link {{ request()->is('settings*') ? '' : 'collapsed' }}"
        data-bs-toggle="collapse">
        <span class="sidebar-icon"><i class="fas fa-cog"></i></span>
        <span class="sidebar-text">Pengaturan</span>
        <span class="ms-auto"><i class="fas fa-chevron-down"></i></span>
      </a>
      <div class="collapse {{ request()->is('settings*') ? 'show' : '' }}" id="settingsMenu">
        <div class="sidebar-submenu">
          <a href="{{ route('settings.users.index') }}"
            class="sidebar-link {{ request()->routeIs('settings.users.*') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-users"></i></span>
            <span class="sidebar-text">Manajemen User</span>
          </a>
          <a href="{{ route('settings.company.edit') }}"
            class="sidebar-link {{ request()->routeIs('settings.company') ? 'active' : '' }}">
            <span class="sidebar-icon"><i class="fas fa-building"></i></span>
            <span class="sidebar-text">Profil Perusahaan</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- <div class="sidebar-footer p-3">
    <div class="card bg-dark border-0">
      <div class="card-body p-3 text-center">
        <div class="mb-2">
          <i class="fas fa-headset fa-2x text-primary"></i>
        </div>
        <h6 class="mb-1">Butuh Bantuan?</h6>
        <p class="small text-muted mb-2">Hubungi tim support kami</p>
        <a href="mailto:support@tbsogol.com" class="btn btn-sm btn-primary w-100">
          <i class="fas fa-envelope me-1"></i> Email
        </a>
      </div>
    </div>
  </div> --}}
</div>