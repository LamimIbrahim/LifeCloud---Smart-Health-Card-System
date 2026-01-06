<?php

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>LifeCloud | Patient Dashboard</title>
  <style>
    body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#020617;color:#e5e7eb;}
    .wrap{max-width:1200px;margin:0 auto;padding:24px 16px 40px;display:grid;grid-template-columns:260px 1fr;gap:20px;}
    .sidebar{background:#020617;border-radius:16px;border:1px solid rgba(148,163,184,.4);padding:18px 16px;}
    .sidebar h2{margin:0 0 4px;font-size:20px;}
    .sidebar small{color:#9ca3af;}
    .nav{margin-top:18px;}
    .nav a{display:flex;align-items:center;gap:8px;padding:8px 10px;border-radius:10px;text-decoration:none;color:#e5e7eb;font-size:14px;margin-bottom:6px;}
    .nav a.active,.nav a:hover{background:rgba(59,130,246,.18);}
    .content{display:flex;flex-direction:column;gap:18px;}
    .card{background:#020617;border-radius:16px;border:1px solid rgba(148,163,184,.4);padding:18px 20px;box-shadow:0 16px 40px rgba(0,0,0,.35);}
    .card h3{margin:0 0 10px;font-size:16px;}
    .profile-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px 16px;font-size:14px;}
    .pg-label{font-size:11px;color:#9ca3af;}
    .pg-value{font-size:14px;}
    .visit-card{border-radius:12px;border:1px solid rgba(148,163,184,.35);padding:10px 12px;margin-bottom:8px;background:#020617;}
    .visit-title{font-size:14px;font-weight:600;}
    .visit-date{font-size:12px;color:#9ca3af;}
    .quick-row{display:flex;flex-wrap:wrap;gap:12px;margin-top:6px;}
    .quick-btn{flex:1;min-width:160px;border-radius:12px;padding:10px 14px;border:1px solid rgba(148,163,184,.5);background:#020617;color:#e5e7eb;text-decoration:none;font-size:14px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:6px;}
    .quick-btn:hover{border-color:#38bdf8;}
    @media(max-width:900px){.wrap{grid-template-columns:1fr;}}
  </style>
</head>
<body>
  <main class="wrap">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2>Patient Dashboard</h2>
      <small>Welcome back, <?php echo htmlspecialchars($profile['name'] ?? ''); ?></small>

      <nav class="nav">
        <a href="dashboard.php" class="active">🏠 Home</a>
        <a href="patient_appointments.php">📅 Appointment</a>
        <a href="patient_prescriptions.php">💊 Prescription</a>
        <a href="patient_tests.php">🧪 Test Result</a>
        <a href="patient_history.php">📄 Medical History</a>
        <a href="logout.php">🚪 Logout</a>
      </nav>
    </aside>

    <!-- Main content -->
    <section class="content">
      <!-- Profile summary -->
      <section class="card">
        <h3>Profile Summary</h3>
        <?php if (!$profile): ?>
          <p style="font-size:13px;color:#9ca3af;">Profile not found.</p>
        <?php else: ?>
          <div class="profile-grid">
            <div>
              <div class="pg-label">Name</div>
              <div class="pg-value"><?php echo htmlspecialchars($profile['name']); ?></div>
            </div>
            <div>
              <div class="pg-label">Health Card Number</div>
              <div class="pg-value"><?php echo htmlspecialchars($profile['health_card_no']); ?></div>
            </div>
            <div>
              <div class="pg-label">Member Since</div>
              <div class="pg-value">
                <?php echo htmlspecialchars(date('F Y', strtotime($profile['created_at']))); ?>
              </div>
            </div>
            <div>
              <div class="pg-label">Date of Birth</div>
              <div class="pg-value"><?php echo htmlspecialchars($profile['date_of_birth']); ?></div>
            </div>
            <div>
              <div class="pg-label">Gender</div>
              <div class="pg-value"><?php echo htmlspecialchars($profile['gender']); ?></div>
            </div>
            <div>
              <div class="pg-label">Email</div>
              <div class="pg-value"><?php echo htmlspecialchars($profile['email']); ?></div>
            </div>
          </div>
        <?php endif; ?>
      </section>

      <!-- Recent visits -->
      <section class="card">
        <h3>Recent Visits</h3>
        <?php if (empty($recentVisits)): ?>
          <p style="font-size:13px;color:#9ca3af;">No visits recorded yet.</p>
        <?php else: ?>
          <?php foreach ($recentVisits as $v): ?>
            <div class="visit-card">
              <div class="visit-title">
                Dr. <?php echo htmlspecialchars($v['doctor_name']); ?>
                – <?php echo htmlspecialchars($v['title']); ?>
              </div>
              <div class="visit-date">
                <?php echo htmlspecialchars($v['visit_date']); ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>

      <!-- Quick actions -->
      <section class="card">
        <h3>Quick Action</h3>
        <div class="quick-row">
          <a class="quick-btn" href="patient_appointments.php">📅 Book / View Appointment</a>
          <a class="quick-btn" href="patient_tests.php">🧪 Test Results</a>
          <a class="quick-btn" href "patient_history.php">📄 Medical History</a>
        </div>
      </section>
    </section>
  </main>
</body>
</html>
