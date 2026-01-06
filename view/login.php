<?php $msg = $message ?? ""; ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Login</title>

  <style>
    *,
    *::before,
    *::after { box-sizing:border-box; }

    body {
      margin:0;
      font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
      background:radial-gradient(circle at top,#0ea5e9 0,#020617 55%);
      min-height:100vh;
      color:#e5e7eb;
    }
    .wrap {
      min-height:100vh;display:flex;align-items:center;
      justify-content:center;padding:24px 16px;
    }
    .card.login-card {
      background:rgba(15,23,42,0.96);
      border-radius:18px;
      padding:22px 26px 28px;
      box-shadow:0 20px 50px rgba(0,0,0,.55);
      max-width:480px;width:100%;
    }
    .hero{padding:8px 6px 10px;border-bottom:1px solid rgba(148,163,184,.4);margin-bottom:18px;}
    .brand{display:flex;align-items:center;gap:10px;}
    .logo{width:44px;height:44px;border-radius:12px;overflow:hidden;
      display:flex;align-items:center;justify-content:center;background:transparent;}
    .logo img{width:100%;height:100%;object-fit:cover;display:block;}
    .brand-text h1{margin:0;font-size:22px;letter-spacing:.03em;}
    .brand-text p{margin:0;font-size:12px;color:#9ca3af;}
    .login-form{padding:18px 6px 4px;}
    .login-header{
      border:1px solid rgba(148,163,184,.6);
      width:fit-content;margin:0 auto 20px;
      padding:6px 22px;font-size:16px;font-weight:600;
      color:#e5e7eb;border-radius:999px;
      display:flex;align-items:center;gap:8px;
    }
    .error-msg{
      color:#fecaca;text-align:center;
      margin-bottom:10px;font-weight:bold;font-size:13px;
    }
    .field-group{margin-bottom:16px;}
    .field-group label{
      display:flex;align-items:center;gap:6px;
      font-weight:600;color:#e5e7eb;margin-bottom:6px;font-size:14px;
    }
    .field-group .icon{font-size:16px;}
    .input-wrapper input{
      width:100%;background:#020617;border-radius:8px;
      border:1px solid #1f2937;padding:10px 11px;
      font-size:14px;color:#e5e7eb;
    }
    .input-wrapper input::placeholder{color:#6b7280;}
    .input-wrapper input:focus{
      outline:none;border-color:#38bdf8;
      box-shadow:0 0 0 3px rgba(56,189,248,.35);
    }
    .actions{
      margin-top:20px;display:flex;
      flex-direction:column;gap:12px;align-items:center;
    }
    .btn{
      width:100%;border-radius:999px;padding:10px 16px;
      border:none;cursor:pointer;font-weight:700;
      font-size:14px;display:inline-flex;align-items:center;
      justify-content:center;text-decoration:none;
    }
    .btn.primary{background:linear-gradient(135deg,#0ea5e9,#22c55e);color:#0b1120;}
    .btn.primary:hover{filter:brightness(1.05);}
    .btn.link{background:transparent;color:#93c5fd;text-decoration:underline;}
    @media(max-width:480px){.card.login-card{padding:18px 18px 22px;}}
  </style>
</head>
<body>
  <main class="wrap">
    <section class="card login-card">
      <header class="hero">
        <div class="brand">
          <div class="logo"><img src="logo.png" alt="LifeCloud logo"></div>
          <div class="brand-text">
            <h1>LifeCloud</h1>
            <p>Health on Cloud</p>
          </div>
        </div>
      </header>

      <form method="POST" action="" class="login-form" id="loginForm" novalidate>
        <div class="login-header">
          <span class="icon-user">👤</span> Login
        </div>

        <?php if(!empty($msg)): ?>
          <div class="error-msg"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>

        <div class="field-group">
          <label for="uid"><span class="icon">💳</span> Email / Health Card No</label>
          <div class="input-wrapper">
            <input id="uid" name="uid" type="text" required placeholder="Email or Health Card Number" />
          </div>
        </div>

        <div class="field-group">
          <label for="password"><span class="icon">🔒</span> Password</label>
          <div class="input-wrapper">
            <input id="password" name="password" type="password" required placeholder="Enter password" />
          </div>
        </div>

        <div class="actions">
          <button class="btn primary" type="submit">Login</button>
          <a class="btn link" href="register.php">Create New Account</a>
        </div>
      </form>
    </section>
  </main>

  <!-- JS validation -->
  <script>
  document.getElementById('loginForm').addEventListener('submit', function (e) {
    const emailInput = document.getElementById('uid');
    const passInput  = document.getElementById('password');
    let msg = '';

    const value = emailInput.value.trim();
    const pass  = passInput.value;

    if(value === '' || pass === ''){
      msg = 'Email/ID and password are required.';
    } else {
      const looksEmail = value.includes('@');
      if(looksEmail){
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(!re.test(value)){
          msg = 'Please enter a valid email address.';
        }
      }
      if(pass.length < 6){
        msg = 'Password must be at least 6 characters.';
      }
    }

    if(msg !== ''){
      e.preventDefault();
      let box = document.querySelector('.error-msg');
      if(!box){
        box = document.createElement('div');
        box.className = 'error-msg';
        const form = document.getElementById('loginForm');
        form.insertBefore(box, form.querySelector('.field-group'));
      }
      box.textContent = msg;
    }
  });
  </script>
</body>
</html>
