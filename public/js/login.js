document.addEventListener('DOMContentLoaded', function() {
  // Pastikan tinggi card login sesuai dengan konten
  function adjustLoginCardHeight() {
    const loginCard = document.querySelector('.login-card.compact');
    if (loginCard) {
      const windowHeight = window.innerHeight;
      const cardHeight = loginCard.offsetHeight;
      
      // Jika card lebih tinggi dari viewport (hanya untuk mobile)
      if (window.innerWidth <= 768 && cardHeight > windowHeight) {
        loginCard.style.margin = '20px 0';
      } else {
        loginCard.style.margin = '0';
      }
    }
  }

  // Panggil fungsi saat load dan resize
  adjustLoginCardHeight();
  window.addEventListener('resize', adjustLoginCardHeight);

  // Animasi halus saat card muncul
  const loginCard = document.querySelector('.login-card.compact');
  if (loginCard) {
    loginCard.style.opacity = '0';
    loginCard.style.transform = 'scale(0.98)';
    
    setTimeout(() => {
      loginCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      loginCard.style.opacity = '1';
      loginCard.style.transform = 'scale(1)';
    }, 50);
  }
});