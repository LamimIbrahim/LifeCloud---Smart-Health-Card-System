<?php
session_start();
include 'config/db.php';


// only admin access
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit;
}

// patients load
$patients = [];   // IMPORTANT: variable init

$sql = "SELECT id, name, email, created_at 
        FROM users 
        WHERE role = 'patient'
        ORDER BY created_at DESC";

if ($res = $conn->query($sql)) {
    while ($row = $res->fetch_assoc()) {
        $patients[] = $row;
    }
    $res->free();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Patients</title>
  <style>
    body {
      font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
      background:#020617;
      color:#e5e7eb;
      margin:0;
    }

    .page-wrap {
      max-width: 1200px;
      margin: 0 auto;
      padding: 32px 24px 40px;
    }

    a { color:#38bdf8; text-decoration:none; }

    .top-bar {
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:24px;
    }

    .top-bar h2 {
      margin:0;
      font-size:24px;
    }

    .table-card {
      margin-top: 12px;
      background:#020617;
      border-radius:14px;
      border:1px solid rgba(148,163,184,.5);
      box-shadow:0 16px 40px rgba(0,0,0,.35);
      padding:18px 20px 22px;
    }

    .table-card h3 {
      margin:0 0 14px;
      font-size:16px;
    }

    table {
      width:100%;
      border-collapse:collapse;
      font-size:14px;
    }

    th, td {
      padding:10px 8px;
      text-align:left;
    }

    thead th {
      border-bottom:1px solid #1f2937;
      font-weight:600;
    }

    tbody tr:nth-child(even) {
      background:rgba(15,23,42,.65);
    }

    tbody tr:hover {
      background:rgba(37,99,235,.25);
    }
  </style>
</head>
<body>
  <div class="page-wrap">
    <div class="top-bar">
      <h2>Patients</h2>
      <div>
        <a href="admin_dashboard.php">Back to Dashboard</a>
      </div>
    </div>

    <div class="table-card">
      <h3>Patients List</h3>

      <?php if (empty($patients)): ?>
        <p>No patients found.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($patients as $p): ?>
            <tr>
              <td><?php echo (int)$p['id']; ?></td>
              <td><?php echo htmlspecialchars($p['name']); ?></td>
              <td><?php echo htmlspecialchars($p['email']); ?></td>
              <td><?php echo htmlspecialchars($p['created_at']); ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>

    </div>
  </div>
</body>
</html>
