    document.getElementById('togglePassword').addEventListener('click', function () {
      const password = document.getElementById('password');
      const icon = document.getElementById('toggleIcon');
      const isHidden = password.type === 'password';
      password.type = isHidden ? 'text' : 'password';
      icon.classList.toggle('bi-lock-fill', !isHidden);
      icon.classList.toggle('bi-unlock-fill', isHidden);
    });

    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();
      alert('Connexion simulée !');
    });