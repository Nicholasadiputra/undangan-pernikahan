<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nicholas & Nahda — Login Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link rel="icon" type="image/jpg" href="<?php echo e(asset('favicon.jpg')); ?>">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #f5f0ea 0%, #e8ddd0 100%);
      display: flex; align-items: center; justify-content: center;
      font-family: 'DM Sans', sans-serif;
    }
    .card {
      background: #fff;
      border-radius: 20px;
      padding: 48px 40px 40px;
      width: 100%; max-width: 420px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.10);
      text-align: center;
    }
    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 22px; color: #3d2c1e;
      letter-spacing: 2px; line-height: 1.4;
      margin-bottom: 4px;
    }
    .sub { font-size: 13px; color: #9e8878; margin-bottom: 32px; }
    .form-group { text-align: left; margin-bottom: 18px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: #5a4536; margin-bottom: 6px; }
    .form-group input {
      width: 100%; padding: 12px 16px;
      border: 1.5px solid #e0d5c8; border-radius: 10px;
      font-size: 14px; font-family: 'DM Sans', sans-serif;
      color: #3d2c1e; outline: none;
      background: #faf8f5; transition: border-color .2s;
    }
    .form-group input:focus { border-color: #8F7D65; }
    .password-wrapper { position: relative; }
    .toggle-password {
      position: absolute; right: 12px; top: 50%;
      transform: translateY(-50%);
      cursor: pointer; background: none; border: none;
      color: #9e8878; display: flex; align-items: center;
      padding: 4px;
    }
    .toggle-password:hover { color: #5a4536; }
    .btn {
      width: 100%; padding: 13px;
      background: #3d2c1e; color: #fff;
      border: none; border-radius: 10px;
      font-size: 15px; font-weight: 600;
      font-family: 'DM Sans', sans-serif;
      cursor: pointer; margin-top: 6px;
      transition: background .2s;
    }
    .btn:hover { background: #5a4536; }
    .error {
      background: #fdecea; color: #c0392b;
      border-radius: 8px; padding: 10px 14px;
      font-size: 13px; margin-bottom: 18px;
    }
  </style>
</head>
<body>
<div class="card">
  <div class="logo">NICHOLAS<br>&amp; NAHDA</div>
  <div class="sub">Admin Dashboard</div>

  <?php if($errors->has('login')): ?>
    <div class="error"><?php echo e($errors->first('login')); ?></div>
  <?php endif; ?>

  <form method="POST" action="<?php echo e(route('dashboard.login.post')); ?>">
    <?php echo csrf_field(); ?>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username"
             value="<?php echo e(old('username')); ?>"
             placeholder="Masukkan username" required/>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <div class="password-wrapper">
        <input type="password" id="password" name="password"
               placeholder="Masukkan password" required/>
        <button type="button" class="toggle-password" id="togglePassword">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" id="eyeIcon">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
        </button>
      </div>
    </div>
    <button type="submit" class="btn">Masuk</button>
  </form>
</div>
  <script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    toggleBtn.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      
      // Ganti ikon mata jika perlu (opsional, tapi bagus)
      if (type === 'text') {
        eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
      } else {
        eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
      }
    });
  </script>
</body>
</html><?php /**PATH C:\laragon\www\undangan\resources\views/dashboard/login.blade.php ENDPATH**/ ?>