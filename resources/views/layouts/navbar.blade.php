<nav class="navbar navbar-expand navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <button class="btn btn-sm btn-outline-light me-2 d-lg-none" id="sidebarToggle">
      <i class="fas fa-bars"></i>
    </button>

    <form class="d-none d-md-flex ms-3 w-50 search-form">
      <div class="input-group input-group-sm">
        <input type="text" class="form-control" placeholder="Cari barang, supplier..." aria-label="Search">
        <button class="btn btn-light" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>

    <div class="navbar-nav ms-auto align-items-center">
      <button class="btn btn-link text-white d-md-none me-2" id="searchToggle">
        <i class="fas fa-search"></i>
      </button>

      <div class="mobile-search d-none w-100 px-2 py-1 position-absolute start-0 bg-primary">
        <div class="input-group input-group-sm">
          <input type="text" class="form-control" placeholder="Cari...">
          <button class="btn btn-light" type="button">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>

      <div class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown"
          aria-expanded="false">
          <div class="user-info">
            <div class="user-avatar">
              {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </div>
            <div class="user-details d-none d-md-block">
              <span class="user-name">{{ Auth::user()->profile_name ?? Auth::user()->name }}</span>
            </div>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow">
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>

<style>
  /* Profile Styles */
  .user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1rem;
  }

  .user-details {
    display: flex;
    flex-direction: column;
    text-align: left;
  }

  .user-name {
    font-weight: 500;
    font-size: 0.9rem;
  }

  .user-role {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.8);
  }

  /* Mobile Search Adjustments */
  @media (max-width: 767.98px) {
    .mobile-search {
      top: 100%;
    }

    .user-details {
      display: none !important;
    }
  }
</style>

<script>
  // Toggle mobile search
  document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('searchToggle')) {
      document.getElementById('searchToggle').addEventListener('click', function () {
        const mobileSearch = document.querySelector('.mobile-search');
        const navItems = document.querySelectorAll('.navbar-nav > .nav-item');
        navItems.forEach(item => {
          item.classList.toggle('d-none');
        });
        mobileSearch.classList.toggle('d-none');
      });
    }
  });
</script>