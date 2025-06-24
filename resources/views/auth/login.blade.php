<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Inventory Toko Material</title>
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-color: #2c3e50;
      --secondary-color: #3498db;
      --accent-color: #e74c3c;
      --light-color: #ecf0f1;
      --dark-color: #2c3e50;
      --success-color: #2ecc71;
      --warning-color: #f39c12;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, rgba(44, 62, 80, 0.9), rgba(52, 152, 219, 0.8)),
        url('https://images.unsplash.com/photo-1605106702734-205df224ecce?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') no-repeat center center;
      background-size: cover;
      background-attachment: fixed;
      position: relative;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom right,
          rgba(44, 62, 80, 0.8),
          rgba(52, 152, 219, 0.6));
      z-index: -1;
    }

    .login-wrapper {
      width: 100%;
      max-width: 380px;
      padding: 0 15px;
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-card {
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      background-color: rgba(255, 255, 255, 0.98);
      overflow: hidden;
      transition: all 0.3s ease;
      border: none;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    .login-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 1.2rem 1rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .login-header::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-color), var(--warning-color));
    }

    .login-header h2 {
      font-size: 1.4rem;
      font-weight: 600;
      margin: 0.5rem 0 0;
      text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
    }

    .login-header p {
      font-size: 0.9rem;
      opacity: 0.9;
      margin-bottom: 0;
    }

    .logo {
      width: 60px;
      height: 60px;
      background-color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
      border: 3px solid white;
      transition: all 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.05) rotate(5deg);
    }

    .logo img {
      border-radius: 50%;
      object-fit: cover;
    }

    .login-body {
      padding: 1.5rem;
    }

    .form-control {
      font-size: 0.85rem;
      padding: 0.4rem 0.8rem;
      border-radius: 8px;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }

    .form-control:focus {
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    .input-group-text {
      background-color: var(--light-color);
      border-color: #ddd;
      color: var(--primary-color);
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      font-size: 0.9rem;
      font-weight: 500;
      letter-spacing: 0.5px;
      padding: 0.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 7px 14px rgba(0, 0, 0, 0.2);
      background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .form-check-input:checked {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }

    .forgot-password a {
      color: var(--secondary-color);
      text-decoration: none;
      transition: all 0.2s;
    }

    .forgot-password a:hover {
      color: var(--primary-color);
      text-decoration: underline;
    }

    .alert {
      border-radius: 8px;
      font-size: 0.85rem;
    }

    /* Efek floating untuk elemen tertentu */
    .floating {
      animation: floating 3s ease-in-out infinite;
    }

    @keyframes floating {
      0% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-8px);
      }

      100% {
        transform: translateY(0px);
      }
    }

    /* Responsive design */
    @media (max-width: 576px) {
      .login-wrapper {
        max-width: 95%;
        padding: 0 10px;
      }

      .login-header {
        padding: 1rem 0.8rem;
      }

      .login-header h2 {
        font-size: 1.2rem;
      }

      .logo {
        width: 50px;
        height: 50px;
      }

      .login-body {
        padding: 1rem;
      }
    }

    @media (max-width: 400px) {
      .login-header {
        padding: 0.8rem 0.6rem;
      }

      .login-header h2 {
        font-size: 1.1rem;
      }

      .logo {
        width: 45px;
        height: 45px;
      }

      .login-body {
        padding: 0.8rem;
      }

      .btn-login {
        font-size: 0.85rem;
      }
    }

    /* Animasi tambahan */
    .pulse {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.7);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(52, 152, 219, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(52, 152, 219, 0);
      }
    }
  </style>
</head>

<body>
  <div class="login-wrapper">
    <div class="login-card">
      <div class="login-header">
        <div class="logo floating">
          <img src="{{ asset('images/newstruk.png') }}" alt="Logo TB. SOGOL ANUGRAH MANDIRI" width="100%" height="100%">
        </div>
        <h2>TB. SOGOL ANUGRAH MANDIRI</h2>
        <p class="small">Sistem Inventory Toko Material</p>
      </div>
      <div class="login-body">
        <!-- Pesan Error -->
        <div class="alert alert-danger alert-dismissible fade show py-2 d-none" id="errorAlert">
          <i class="fas fa-exclamation-circle me-2"></i>
          <span id="errorMessage"></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <form id="loginForm" method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label small fw-bold">Email</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" class="form-control" id="email" name="email" placeholder="email@contoh.com" required
                autofocus>
            </div>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label small fw-bold">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password"
                required>
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember">
              <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>
            <div class="forgot-password">
              <a href="#" class="small">Lupa password?</a>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-login w-100 py-2 mb-2 pulse">
            <i class="fas fa-sign-in-alt me-2"></i>MASUK
          </button>
        </form>
        <div class="text-center mt-2">
          <p class="small text-muted">
            <i class="fas fa-info-circle me-1"></i> Untuk mendapatkan akses, hubungi administrator sistem.
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Toggle password visibility
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#password');

      if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
      }

      // Simulasi error message (untuk demo)
      const urlParams = new URLSearchParams(window.location.search);
      const error = urlParams.get('error');

      if (error) {
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');

        errorMessage.textContent = decodeURIComponent(error);
        errorAlert.classList.remove('d-none');

        // Auto hide setelah 5 detik
        setTimeout(() => {
          const alert = new bootstrap.Alert(errorAlert);
          alert.close();
        }, 5000);
      }

      // Form submission animation
      const loginForm = document.getElementById('loginForm');
      if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
          const btn = this.querySelector('button[type="submit"]');
          if (btn) {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            btn.disabled = true;
          }
        });
      }

      // Floating animation untuk logo
      const logo = document.querySelector('.logo');
      if (logo) {
        logo.addEventListener('mouseenter', function () {
          this.style.animation = 'none';
          setTimeout(() => {
            this.style.animation = 'floating 3s ease-in-out infinite';
          }, 10);
        });
      }
    });
  </script>
</body>

</html>