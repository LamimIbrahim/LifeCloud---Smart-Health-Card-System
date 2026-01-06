<?php // $pendingUsers, $message from AdminController::pending() ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LifeCloud | Pending Approvals</title>
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
    .section-card{background:#020617;border-radius:14px;border:1px solid rgba(148,163,184,.4);
                  padding:16px 18px 18px;box-shadow:0 16px 40px rgba(0,0,0,.35);}
    table{width:100%;border-collapse:collapse;font-size:13px;}
    th,td{padding:6px 4px;text-align:left;border-bottom:1px solid rgba(30,64,175,.5);}
    th{font-weight:600;color:#9ca3af;}
    .status-badge{padding:2px 8px;border-radius:999px;font-size:11px;text-transform:capitalize;display:inline-block;}
    .status-pending{background:rgba(234,179,8,.15);color:#facc15;}
    .inline-form{display:inline;}
    .btn-small{border-radius:999px;border:none;padding:4px 10px;font-size:11px;cursor:pointer;margin-right:4px;}
    .btn-approve{background:rgba(34,197,94,.2);color:#bbf7d0;border:1px solid rgba(34,197,94,.6);}
    .btn-reject{background:rgba(239,68,68,.15);color:#fecaca;border:1px solid rgba(239,68,68,.6);}
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
      <a href="admin_doctors.php" class="nav-item">
        <span class="nav-icon">👨‍⚕️</span><span>Add / View Doctors</span>
      </a>
      <a href="admin_users.php" class="nav-item">
        <span class="nav-icon">👥</span><span>All Users</span>
      </a>
      <a href="admin_pending.php" class="nav-item active">
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
    <h1>Pending Approvals</h1>

    <?php if (!empty($message)): ?>
      <div class="msg"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <section class="section-card">
      <?php if (empty($pendingUsers)): ?>
        <p style="font-size:13px;color:#9ca3af;">No pending users.</p>
      <?php else: ?>
        <table>
          <thead>
          <tr>
            <th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($pendingUsers as $u): ?>
            <tr>
              <td><?php echo htmlspecialchars($u['name']); ?></td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td><?php echo htmlspecialchars(ucfirst($u['role'])); ?></td>
              <td>
                <span class="status-badge status-pending">
                  <?php echo htmlspecialchars($u['status']); ?>
                </span>
              </td>
              <td><?php echo htmlspecialchars($u['created_at']); ?></td>
              <td>
                <form class="inline-form" method="post">
                  <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                  <button class="btn-small btn-approve" name="action" value="approve" type="submit">
                    Approve
                  </button>
                </form>
                <form class="inline-form" method="post">
                  <input type="hidden" name="user_id" value="<?php echo (int)$u['id']; ?>">
                  <button class="btn-small btn-reject" name="action" value="reject" type="submit">
                    Reject
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
