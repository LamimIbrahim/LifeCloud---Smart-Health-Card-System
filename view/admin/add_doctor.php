<?php // $message from AdminController::addDoctorForm() ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LifeCloud | Add Doctor</title>
  <style>
    body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
         background:#020617;color:#e5e7eb;display:flex;min-height:100vh;}
    .sidebar{width:260px;background:#020617;border-right:1px solid #111827;
             padding:24px 18px;display:flex;flex-direction:column;gap:16px;}
    .sidebar-title{font-size:20px;font-weight:600;margin-bottom:4px;}
    .sidebar-sub{font-size:13px;color:#9ca3af;}
    .nav-section{margin-top:20px;}
    .nav-item{display:flex;align-items:center;gap:10px;padding:8px 10px;
              border-radius:10px;font-size:14px;color:#e5e7eb;text-decoration:none;}
    .nav-item.active{background:#0f172a;}
    .nav-item:hover{background:#020617;border:1px solid #1d4ed8;}
    .nav-icon{width:18px;text-align:center;}
    .sidebar-footer{margin-top:auto;font-size:12px;color:#6b7280;}
    .logout{margin-top:8px;color:#fecaca;}
    a{color:inherit;text-decoration:none;}
    .main{flex:1;padding:24px 24px 32px;overflow-y:auto;}
    h1{margin:0 0 16px;font-size:22px;}
    .msg{margin-bottom:12px;font-size:13px;padding:8px 10px;border-radius:8px;
         background:rgba(52,211,153,.15);border:1px solid rgba(52,211,153,.5);color:#bbf7d0;}
    .form-card{background:#020617;border-radius:14px;border:1px solid rgba(148,163,184,.4);
               padding:16px 18px 18px;box-shadow:0 16px 40px rgba(0,0,0,.35);max-width:520px;}
    .field{margin-bottom:10px;}
    label{font-size:13px;display:block;margin-bottom:4px;color:#9ca3af;}
    input[type=text],input[type=email],input[type=password]{
      width:100%;padding:8px 10px;border-radius:8px;border:1px solid #1f2937;
      background:#020617;color:#e5e7eb;font-size:14px;
    }
    .btn-primary{
      margin-top:8px;padding:8px 16px;border-radius:999px;border:none;
      background:#2563eb;color:#e5e7eb;font-size:14px;font-weight:600;cursor:pointer;
    }
    .btn-primary:hover{background:#1d4ed8;}
  </style>
</head>
<body>
  <aside class="sidebar">
    <div>
      <div class="sidebar-title">Admin Dashboard</div>
      <div class="sidebar-sub">Welcome back, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></div>
    </div>

    <div class="nav-section">
      <a href="admin_dashboard.php" class="nav-item">
        <span class="nav-icon">🏠</span><span>Home</span>
      </a>
      <a href="admin_add_doctor.php" class="nav-item active">
        <span class="nav-icon">➕</span><span>Add Doctor</span>
      </a>
      <a href="admin_doctors.php" class="nav-item">
        <span class="nav-icon">👨‍⚕️</span><span>View Doctors</span>
      </a>
      <a href="admin_users.php" class="nav-item">
        <span class="nav-icon">👥</span><span>All Users</span>
      </a>
      <a href="admin_pending.php" class="nav-item">
        <span class="nav-icon">⏳</span><span>Pending Approvals</span>
      </a>
    </div>

    <div class="sidebar-footer">
      <div>Quick Access</div>
      <a href="logout.php" class="nav-item logout">
        <span class="nav-icon">🚪</span><span>Logout</span>
      </a>
    </div>
  </aside>

  <main class="main">
    <h1>Add Doctor</h1>

    <?php if (!empty($message)): ?>
      <div class="msg"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="form-card">
      <form method="post" action="admin_add_doctor_submit.php">
        <div class="field">
          <label for="name">Full Name *</label>
          <input type="text" id="name" name="name" required>
        </div>

        <div class="field">
          <label for="email">Email *</label>
          <input type="email" id="email" name="email" required>
        </div>

        <div class="field">
          <label for="password">Password *</label>
          <input type="password" id="password" name="password" required>
        </div>

        <div class="field">
          <label for="specialization">Specialization *</label>
          <input type="text" id="specialization" name="specialization" required>
        </div>

        <div class="field">
          <label for="phone">Phone</label>
          <input type="text" id="phone" name="phone">
        </div>

        <div class="field">
          <label for="room_no">Room No</label>
          <input type="text" id="room_no" name="room_no">
        </div>

        <button type="submit" class="btn-primary">Save Doctor</button>
      </form>
    </div>
  </main>
</body>
</html>
