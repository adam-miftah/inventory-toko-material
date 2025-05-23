<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('TB. SOGOL ANUGRAH MANDIRI', 'TB. SOGOL ANUGRAH MANDIRI') }} - @yield('title')</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <style>
    :root {
      --primary: #06609CFF;
      --secondary: #2c3e50;
      --success: #27ae60;
      --danger: #e74c3c;
      --warning: #f39c12;
      --info: #2980b9;
      --light: #ecf0f1;
      --dark: #2c3e50;
      --sidebar-width: 250px;
      --topbar-height: 60px;
    }

    * {
      font-family: 'Poppins', sans-serif;
      box-sizing: border-box;
    }

    body {
      background-color: #f5f7fa;
      overflow-x: hidden;
    }

    /* Layout */
    .wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .sidebar {
      width: var(--sidebar-width);
      background: var(--primary);
      color: white;
      position: fixed;
      height: 100vh;
      z-index: 1050;
      transition: all 0.3s ease;
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
      overflow-y: auto;
    }

    .main-content {
      margin-left: var(--sidebar-width);
      width: calc(100% - var(--sidebar-width));
      transition: all 0.3s ease;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .content {
      margin-top: var(--topbar-height);
      flex: 1;
      padding: 20px;
    }

    /* Navbar */
    .navbar {
      height: var(--topbar-height);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      right: 0;
      left: var(--sidebar-width);
      z-index: 1040;
      transition: all 0.3s ease;
    }

    /* Cards */
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      transition: all 0.3s;
      margin-bottom: 1.5rem;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background: white;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      font-weight: 600;
    }

    /* Buttons */
    .btn {
      border-radius: 8px;
      padding: 8px 16px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border: none;
    }

    .sidebar-divider {
      height: 2px;
      background-color: rgba(255, 255, 255, 0.2);
      margin-top: -14px;
    }

    /* Sidebar Menu */
    .sidebar-menu {
      padding: 0;
      list-style: none;
    }

    .sidebar-item {
      margin-bottom: 5px;
    }

    .sidebar-link {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
      border-radius: 5px;
      transition: all 0.3s;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
      background: rgba(255, 255, 255, 0.1);
      color: white;
    }

    .sidebar-icon {
      width: 24px;
      text-align: center;
      margin-right: 10px;
      font-size: 18px;
    }

    .sidebar-text {
      flex-grow: 1;
    }

    .sidebar-badge {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50px;
      padding: 2px 8px;
      font-size: 12px;
    }

    .sidebar-submenu {
      padding-left: 0;
    }

    .sidebar-submenu .sidebar-link {
      padding: 8px 15px 8px 40px;
      font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 992px) {
      .sidebar {
        left: calc(-1 * var(--sidebar-width));
      }

      .sidebar.show {
        left: 0;
      }

      .main-content {
        margin-left: 0;
        width: 100%;
      }

      .navbar {
        left: 0;
      }
    }

    @media (max-width: 768px) {
      .content {
        padding: 15px;
      }

      .sidebar {
        width: 220px;
      }
    }

    @media (max-width: 576px) {
      .content {
        padding: 10px;
      }

      .navbar-brand {
        font-size: 1rem;
      }

      .search-form {
        display: none;
      }
    }

    /* Animations */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-fade {
      animation: fadeIn 0.5s ease forwards;
    }

    /* Utility */
    .hover-scale {
      transition: transform 0.3s;
    }

    .hover-scale:hover {
      transform: scale(1.03);
    }

    .text-gradient {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }

    /* Loading Overlay */
    #loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.9);
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid var(--primary);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /* Backdrop for mobile sidebar */
    .sidebar-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1040;
      display: none;
    }
  </style>

  @stack('styles')
</head>

<body class="bg-light">
  <!-- Loading Overlay -->
  <div id="loading-overlay">
    <div class="spinner"></div>
    <p class="mt-3 text-muted">Memuat Sistem...</p>
  </div>

  <!-- Backdrop for mobile sidebar -->
  <div class="sidebar-backdrop"></div>

  <!-- Wrapper -->
  <div class="wrapper">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content">
      <!-- Navbar -->
      @include('layouts.navbar')

      <!-- Content -->
      <main class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </main>

      <!-- Footer -->
      @include('layouts.footer')
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Hide loading overlay when page is loaded
    window.addEventListener('load', function () {
      setTimeout(function () {
        document.getElementById('loading-overlay').style.opacity = '0';
        setTimeout(function () {
          document.getElementById('loading-overlay').style.display = 'none';
        }, 300);
      }, 500);
    });

    // Mobile sidebar toggle
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.querySelector('.sidebar');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebarClose = document.getElementById('sidebarClose');
      const sidebarBackdrop = document.querySelector('.sidebar-backdrop');

      // Toggle sidebar on mobile
      sidebarToggle.addEventListener('click', function () {
        sidebar.classList.add('show');
        sidebarBackdrop.style.display = 'block';
        document.body.style.overflow = 'hidden';
      });

      // Close sidebar
      sidebarClose.addEventListener('click', function () {
        sidebar.classList.remove('show');
        sidebarBackdrop.style.display = 'none';
        document.body.style.overflow = '';
      });

      // Close sidebar when clicking on backdrop
      sidebarBackdrop.addEventListener('click', function () {
        sidebar.classList.remove('show');
        sidebarBackdrop.style.display = 'none';
        document.body.style.overflow = '';
      });

      // Close dropdown menus when clicking outside
      document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown') && !e.target.classList.contains('dropdown-toggle')) {
          const dropdowns = document.querySelectorAll('.dropdown-menu');
          dropdowns.forEach(function (dropdown) {
            if (dropdown.classList.contains('show')) {
              dropdown.classList.remove('show');
            }
          });
        }
      });

      // Add active class to current route
      const currentPath = window.location.pathname;
      const sidebarLinks = document.querySelectorAll('.sidebar-link');

      sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
          link.classList.add('active');
          // Expand parent collapse if exists
          const parentCollapse = link.closest('.collapse');
          if (parentCollapse) {
            parentCollapse.classList.add('show');
          }
        }
      });

      // Custom JavaScript to close other open collapses when one is toggled
      document.querySelectorAll('.sidebar-link[data-bs-toggle="collapse"]').forEach(function (element) {
        element.addEventListener('click', function () {
          const targetId = this.getAttribute('href');
          document.querySelectorAll('.collapse.show').forEach(function (openCollapse) {
            if ('#' + openCollapse.id !== targetId) {
              const bsCollapse = new bootstrap.Collapse(openCollapse, {
                toggle: false
              });
              bsCollapse.hide();
            }
          });
        });
      });
    });

    // Global error handling for AJAX requests
    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {
      if (jqxhr.status === 419) { // CSRF token mismatch
        Swal.fire({
          title: 'Session Expired',
          text: 'Your session has expired. Please refresh the page.',
          icon: 'error',
          confirmButtonText: 'Refresh'
        }).then(() => {
          window.location.reload();
        });
      } else if (jqxhr.status === 500) {
        Swal.fire({
          title: 'Server Error',
          text: 'An unexpected error occurred. Please try again later.',
          icon: 'error'
        });
      }
    });
  </script>

  @stack('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</body>

</html>