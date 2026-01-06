<?php $msg = $message ?? ""; 
$msg_type = $message_type ?? "";
 ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Registration</title>


  <!-- SAME color combination as login.php -->
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }


    body {
      margin: 0;
      font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
      background: radial-gradient(circle at top, #0ea5e9 0, #020617 55%);
      min-height: 100vh;
      color: #e5e7eb;
    }


    .wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px 16px;
    }


    .card {
      background: rgba(15, 23, 42, 0.96);
      border-radius: 18px;
      padding: 22px 26px 28px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, .55);
      max-width: 720px;
      width: 100%;
    }


    .hero {
      padding: 8px 6px 14px;
      border-bottom: 1px solid rgba(148,163,184,.4);
      margin-bottom: 18px;
      text-align: center;
    }


    .brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 6px;
    }


    .logo {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      background: transparent;
    }


    .logo img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }


    .brand-text h1 {
      margin: 0;
      font-size: 22px;
      letter-spacing: .03em;
    }


    .brand-text p {
      margin: 0;
      font-size: 12px;
      color: #9ca3af;
    }


    .title {
      margin: 8px 0 0;
      font-size: 22px;
      font-weight: 700;
    }


    .alert {
      padding: 10px;
      margin: 12px 6px 0;
      border-radius: 8px;
      text-align: center;
      font-weight: 700;
      font-size: 13px;
    }
    .alert.error {
      background-color: rgba(248,113,113,.15);
      color: #fecaca;
      border:1px solid rgba(254,202,202,.4);
    }
    .alert.success {
      background-color: rgba(52,211,153,.18);
      color: #bbf7d0;
      border:1px solid rgba(187,247,208,.5);
    }


    form {
      padding: 18px 6px 4px;
    }


    .grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px 14px;
    }


    .field {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }


    .field.full {
      grid-column: 1 / -1;
    }


    label {
      display: flex;
      align-items: center;
      gap: 6px;
      font-weight: 600;
      color: #e5e7eb;
      font-size: 14px;
    }


    input,
    select {
      padding: 9px 10px;
      border-radius: 8px;
      background: #020617;
      color: #e5e7eb;
      border: 1px solid #1f2937;
      font-size: 14px;
    }


    input::placeholder {
      color: #6b7280;
    }


    select {
      appearance: none;
      background-image:
        linear-gradient(45deg, transparent 50%, #9ca3af 50%),
        linear-gradient(135deg, #9ca3af 50%, transparent 50%);
      background-position: calc(100% - 18px) 50%, calc(100% - 12px) 50%;
      background-size: 6px 6px, 6px 6px;
      background-repeat: no-repeat;
    }


    input:focus,
    select:focus {
      outline: none;
      border-color: #38bdf8;
      box-shadow: 0 0 0 3px rgba(56,189,248,.35);
    }


    .error {
      min-height: 16px;
      font-size: 12px;
      color: #fca5a5;
    }


    .actions {
      margin-top: 18px;
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
    }


    .btn {
      width: 100%;
      border-radius: 999px;
      padding: 10px 16px;
      border: none;
      cursor: pointer;
      font-weight: 700;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
    }


    .btn.primary {
      background: linear-gradient(135deg,#0ea5e9,#22c55e);
      color: #0b1120;
    }


    .btn.primary:hover {
      filter: brightness(1.05);
    }


    .btn.link {
      background: transparent;
      color: #93c5fd;
      text-decoration: underline;
    }


    @media (max-width: 720px) {
      .card {
        padding: 18px 18px 22px;
      }
      .grid {
        grid-template-columns: 1fr;
      }
      .field.full {
        grid-column: auto;
      }
    }
  </style>
</head>
<body>
  <main class="wrap">
    <section class="card">
      <header class="hero">
        <div class="brand">
          <div class="logo">
            <img src="logo.png" alt="LifeCloud logo">
          </div>
          <div class="brand-text">
            <h1>LifeCloud</h1>
            <p>Health on Cloud</p>
          </div>
        </div>
        <h2 class="title">Registration</h2>
      </header>


      <?php if ($msg !== ""): ?>
        <div class="alert <?php echo htmlspecialchars($msg_type); ?>">
          <?php echo htmlspecialchars($msg); ?>
        </div>
      <?php endif; ?>


      <form method="POST" id="regForm" novalidate>
        <div class="grid">
          <div class="field full">
            <label for="fullName">Full Name</label>
            <input id="fullName" name="fullName" type="text" required minlength="3"
                   placeholder="Enter full name" />
            <small class="error" id="err-fullName"></small>
          </div>


          <div class="field">
            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
              <option value="" selected disabled>Select</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <small class="error" id="err-gender"></small>
          </div>


          <div class="field">
            <label for="dob">Date of Birth</label>
            <input id="dob" name="dob" type="date" required />
            <small class="error" id="err-dob"></small>
          </div>


          <div class="field full">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required
                   placeholder="example@email.com" />
            <small class="error" id="err-email"></small>
          </div>


          <div class="field full">
            <label for="address">Address</label>
            <input id="address" name="address" type="text" required
                   placeholder="Your address" />
            <small class="error" id="err-address"></small>
          </div>


          <div class="field">
            <label for="role">Role</label>
            <select id="role" name="role" required>
              <option value="" disabled selected>Select role</option>
              <option value="patient">Patient</option>
              <option value="doctor">Doctor</option>
              <option value="admin">Admin</option>
            </select>
            <small class="error" id="err-role"></small>
          </div>


          <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required minlength="6"
                   placeholder="Min 6 chars" />
            <small class="error" id="err-password"></small>
          </div>


          <div class="field">
            <label for="confirmPassword">Confirm Password</label>
            <input id="confirmPassword" name="confirmPassword" type="password" required
                   placeholder="Re-type password" />
            <small class="error" id="err-confirmPassword"></small>
          </div>
        </div>


        <div class="actions">
          <button class="btn primary" type="submit">Registration</button>
          <a class="btn link" href="login.php">Already Have Account? Login Here</a>
        </div>
      </form>
    </section>
  </main>


  <script src="script.js"></script>
</body>
</html>