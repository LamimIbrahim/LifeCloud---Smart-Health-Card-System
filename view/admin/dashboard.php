<?php ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LifeCloud | Admin Dashboard</title>

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

    .dashboard-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 24px 16px 32px;
      min-height: 100vh;
    }

    .top-bar {
      display: flex;
      justify-content: center;
      margin-bottom: 24px;
    }

    .brand-centered {
      display: flex;
      align-items: center;
      gap: 10px;
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
      color: #e5e7eb;
    }

    .brand-text p {
      margin: 0;
      font-size: 12px;
      color: #9ca3af;
    }

    .dashboard-grid {
      display: grid;
      grid-template-columns: 260px 1fr;
      gap: 24px;
      align-items: flex-start;
    }

    .sidebar .welcome-box {
      margin-bottom: 20px;
    }

    .welcome-box h2 {
      margin: 0 0 6px;
      font-size: 18px;
      color: #e5e7eb;
    }

    .welcome-box p {
      margin: 0;
      font-size: 13px;
      color: #9ca3af;
    }

    .nav-menu {
      border-radius: 14px;
      padding: 18px;
      background: #020617;
      border: 1px solid rgba(148,163,184,.5);
      box-shadow: 0 16px 40px rgba(0,0,0,.35);
    }

    .nav-menu h3 {
      margin: 0 0 14px;
      font-size: 15px;
      text-align: center;
      border-bottom: 1px solid rgba(30,64,175,.6);
      padding-bottom: 8px;
      color: #e5e7eb;
    }

    .nav-menu ul {
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-menu li {
      margin-bottom: 10px;
    }

    .nav-menu a {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-size: 14px;
      color: #cbd5f5;
      padding: 8px 10px;
      border-radius: 8px;
      transition: background 0.15s, color 0.15s;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
      background: rgba(59,130,246,.18);
      color: #e5e7eb;
      font-weight: 600;
    }

    .content-area {
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .dash-card {
      border-radius: 14px;
      padding: 18px 20px 20px;
      background: #020617;
      border: 1px solid rgba(148,163,184,.5);
      box-shadow: 0 16px 40px rgba(0,0,0,.35);
    }

    .card-header h3 {
      margin: 0 0 14px;
      font-size: 16px;
      color: #e5e7eb;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .profile-info {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .info-item {
      display: flex;
      flex-direction: column;
    }

    .info-item small {
      font-size: 12px;
      color: #9ca3af;
      margin-bottom: 2px;
    }

    .info-item strong {
      font-size: 15px;
      color: #e5e7eb;
    }

    .action-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 12px;
    }

    .action-btn {
      flex: 1;
      min-width: 150px;
      border-radius: 10px;
      border: 1px solid rgba(148,163,184,.6);
      background: #020617;
      padding: 12px 14px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      color: #e5e7eb;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      transition: background 0.15s, border-color 0.15s;
    }

    .action-btn:hover {
      background: rgba(59,130,246,.18);
      border-color: #38bdf8;
    }

    .btn-icon {
      font-size: 16px;
    }

    @media (max-width: 900px) {
      .dashboard-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <main class="dashboard-container">
    <header class="top-bar">
      <div class="brand-centered">
        <div class="logo">
          <img src="logo.png" alt="LifeCloud logo">
        </div>
        <div class="brand-text">
          <h1>LifeCloud</h1>
          <p>Admin Dashboard</p>
        </div>
      </div>
    </header>

    <section class="dashboard-grid">
      <aside class="sidebar">
        <div class="welcome-box">
          <h2>Hello, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></h2>
          <p>You are managing LifeCloud system users &amp; records.</p>
        </div>

        <nav class="nav-menu">
          <h3>Admin Menu</h3>
          <ul>
            <li><a href="admin_dashboard.php" class="active">Overview</a></li>
            <li><a href="admin_users.php">Manage Users</a></li>
            <li><a href="admin_patients.php">Patients</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </nav>
      </aside>

      <section class="content-area">
        <!-- System Overview -->
        <article class="dash-card">
          <div class="card-header">
            <h3>System Overview</h3>
          </div>
          <div class="profile-info">
            <div class="info-item">
              <small>Total Users</small>
              <strong><?php echo (int)$stats['total_users']; ?></strong>
            </div>
            <div class="info-item">
              <small>Doctors</small>
              <strong><?php echo (int)$stats['total_doctors']; ?></strong>
            </div>
            <div class="info-item">
              <small>Patients</small>
              <strong><?php echo (int)$stats['total_patients']; ?></strong>
            </div>
            <div class="info-item">
              <small>Pending Approvals</small>
              <strong><?php echo (int)$stats['pending_users']; ?></strong>
            </div>
          </div>
        </article>

        <!-- Latest registrations -->
        <article class="dash-card">
          <div class="card-header">
            <h3>Latest Registrations</h3>
          </div>
          <?php if (empty($recentUsers)): ?>
            <p>No users found.</p>
          <?php else: ?>
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
              <thead>
              <tr>
                <th style="text-align:left; padding:6px 4px;">Name</th>
                <th style="text-align:left; padding:6px 4px;">Email</th>
                <th style="text-align:left; padding:6px 4px;">Role</th>
                <th style="text-align:left; padding:6px 4px;">Status</th>
                <th style="text-align:left; padding:6px 4px;">Joined</th>
              </tr>
              </thead>
              <tbody>
              <?php foreach ($recentUsers as $u): ?>
                <tr>
                  <td style="padding:4px;"><?php echo htmlspecialchars($u['name']); ?></td>
                  <td style="padding:4px;"><?php echo htmlspecialchars($u['email']); ?></td>
                  <td style="padding:4px;"><?php echo htmlspecialchars(ucfirst($u['role'])); ?></td>
                  <td style="padding:4px;"><?php echo htmlspecialchars(ucfirst($u['status'])); ?></td>
                  <td style="padding:4px;"><?php echo htmlspecialchars($u['created_at']); ?></td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </article>

        <!-- Quick Actions -->
       <article class="dash-card">
  <div class="card-header">
    <h3>Quick Actions</h3>
  </div>
  <div class="action-buttons">
    <a href="admin_doctors.php" class="action-btn">
      <span class="btn-icon">👨‍⚕️</span>
      <span>Add Doctor</span>
    </a>
    <a href="admin_users.php" class="action-btn">
      <span class="btn-icon">👥</span>
      <span>View All Users</span>
    </a>
    <a href="admin_users.php#pending" class="action-btn">
      <span class="btn-icon">⏳</span>
      <span>Pending Approvals</span>
    </a>
    <a href="admin_patients.php" class="action-btn">
      <span class="btn-icon">🧑‍⚕️</span>
      <span>View Patients</span>
    </a>
  </div>
</article>

      </section>
    </section>
  </main>
</body>
</html>
