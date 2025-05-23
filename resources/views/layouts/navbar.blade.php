<nav class="navbar navbar-expand navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <!-- Toggle Sidebar (Mobile) -->
    <button class="btn btn-sm btn-outline-light me-2 d-lg-none" id="sidebarToggle">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Brand -->
    {{-- <a class="navbar-brand d-none d-md-block" href="{{ route('dashboard') }}">
      <strong>{{ config('app.name', 'Toko Material') }}</strong>
    </a> --}}

    <!-- Search Bar -->
    <form class="d-none d-md-flex ms-3 w-50 search-form">
      <div class="input-group input-group-sm">
        <input type="text" class="form-control" placeholder="Cari barang, supplier..." aria-label="Search">
        <button class="btn btn-light" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>

    <!-- Right Menu -->
    <div class="navbar-nav ms-auto align-items-center">
      <!-- Search Toggle (Mobile) -->
      <button class="btn btn-link text-white d-md-none me-2" id="searchToggle">
        <i class="fas fa-search"></i>
      </button>

      <!-- Mobile Search Bar (Hidden by default) -->
      <div class="mobile-search d-none w-100 px-2 py-1">
        <div class="input-group input-group-sm">
          <input type="text" class="form-control" placeholder="Cari...">
          <button class="btn btn-light" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>

      <!-- Notifications -->
      {{-- <div class="nav-item dropdown me-2">
        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            3
            <span class="visually-hidden">unread notifications</span>
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg p-0 shadow">
          <div class="dropdown-header bg-light">
            <h6 class="mb-0">Notifikasi</h6>
          </div>
          <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex align-items-center">
                <div class="me-3 text-danger">
                  <i class="fas fa-exclamation-circle fa-lg"></i>
                </div>
                <div>
                  <p class="mb-0 small">Stok semen hampir habis</p>
                  <small class="text-muted">2 menit lalu</small>
                </div>
              </div>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex align-items-center">
                <div class="me-3 text-success">
                  <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div>
                  <p class="mb-0 small">Pembelian berhasil diproses</p>
                  <small class="text-muted">1 jam lalu</small>
                </div>
              </div>
            </a>
          </div>
          <div class="dropdown-footer text-center p-2">
            <a href="#" class="small">Lihat Semua</a>
          </div>
        </div>
      </div> --}}

      <!-- User Menu -->
      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <div class="avatar me-2">
            <img src="{{ asset('images/Profil.png') }}" class="rounded-circle" width="30" height="30" alt="User">
          </div>
          <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li>
            <a class="dropdown-item" href="#">
              <i class="fas fa-user me-2"></i> Profil
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="#">
              <i class="fas fa-cog me-2"></i> Pengaturan
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<script>
  // Toggle mobile search
  document.getElementById('searchToggle').addEventListener('click', function () {
    const mobileSearch = document.querySelector('.mobile-search');
    mobileSearch.classList.toggle('d-none');
    mobileSearch.classList.toggle('d-block');
  });
</script>