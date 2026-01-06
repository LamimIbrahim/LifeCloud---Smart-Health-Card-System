<?php // $doctors, $search from AdminController::doctors() ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LifeCloud | Doctors</title>
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
    .top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;}

    .btn-add{
      padding:8px 14px;border-radius:999px;border:none;
      background:#22c55e;color:#022c22;font-size:13px;font-weight:600;
      cursor:pointer;text-decoration:none;
    }
    .btn-add:hover{background:#16a34a;}

    form.search-form{margin:10px 0 16px;display:flex;gap:8px;}
    form.search-form input[type=text]{
      flex:1;padding:8px 10px;border-radius:999px;border:1px solid #1f2937;
      background:#020617;color:#e5e7eb;font-size:14px;
    }
    form.search-form button{
      padding:8px 16px;border-radius:999px;border:none;
      background:#2563eb;color:#e5e7eb;font-size:14px;font-weight:600;cursor:pointer;
    }
    form.search-form button:hover{background:#1d4ed8;}

    .section-card{background:#020617;border-radius:14px;border:1px solid rgba(148,163,184,.4);
                  padding:16px 18px 18px;box-shadow:0 16px 40px rgba(0,0,0,.35);}
    table{width:100%;border-collapse:collapse;font-size:13px;margin-top:8px;}
    th,td{padding:6px 4px;text-align:left;border-bottom:1px solid rgba(30,64,175,.5);}
    th{font-weight:600;color:#9ca3af;}
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
      <a href="admin_doctors.php" class="nav-item active">
        <span class="nav-icon">👨‍⚕️</span><span>Add / View Doctors</span>
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
    <div class="top-bar">
      <h1>Doctors</h1>

      <a href="admin_add_doctor.php" class="btn-add">
        + Add Doctor
      </a>
    </div>

    
    <form class="search-form" method="get" action="admin_doctors.php" autocomplete="off">
      <input
        type="text"
        name="q"
        placeholder="Search doctor name or specialist"
        autocomplete="off"
        value="<?php echo htmlspecialchars($search ?? ''); ?>"
      >
      <button type="submit">Search</button>
    </form>

    <section class="section-card">
      <?php if (empty($doctors)): ?>
        <p style="font-size:13px;color:#9ca3af;">No doctors found.</p>
      <?php else: ?>
        <table>
          <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Specialist</th><th>Phone</th><th>Room</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($doctors as $d): ?>
            <tr>
              <td><?php echo htmlspecialchars($d['name']); ?></td>
              <td><?php echo htmlspecialchars($d['email']); ?></td>
              <td><?php echo htmlspecialchars($d['specialization']); ?></td>
              <td><?php echo htmlspecialchars($d['phone']); ?></td>
              <td><?php echo htmlspecialchars($d['room_no']); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
