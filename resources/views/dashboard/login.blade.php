<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nicholas & Nahda — Login Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
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

  @if ($errors->has('login'))
    <div class="error">{{ $errors->first('login') }}</div>
  @endif

  <form method="POST" action="{{ route('dashboard.login.post') }}">
    @csrf
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username"
             value="{{ old('username') }}"
             placeholder="Masukkan username" required/>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" id="password" name="password"
             placeholder="Masukkan password" required/>
    </div>
    <button type="submit" class="btn">Masuk</button>
  </form>
</div>
</body>
</html>