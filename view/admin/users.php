<?php
// $pendingUsers, $allUsers, $msg asche AdminController::users() theke
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LifeCloud | Manage Users</title>
  <style>
    body{
      margin:0;
      font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
      background:#020617;
      color:#e5e7eb;
    }
    .wrap{
      max-width:1100px;
      margin:0 auto;
      padding:24px 16px 32px;
    }
    h1{margin:0 0 16px;font-size:22px;}
    a{color:#93c5fd;text-decoration:none;}
    a:hover{text-decoration:underline;}
    .msg{
      margin-bottom:12px;
      font-size:13px;
      padding:8px 10px;
      border-radius:8px;
      background:rgba(52,211,153,.15);
      border:1px solid rgba(52,211,153,.5);
      color:#bbf7d0;
    }
    .card{
      background:#020617;
      border-radius:14px;
      border:1px solid rgba(148,163,184,.4);
      padding:16px 18px 18px;
      box-shadow:0 16px 40px rgba(0,0,0,.35);
      margin-bottom:18px;
    }
    .card h2{
      margin:0 0 10px;
      font-size:18px;
    }
    table{
      width:100%;
      border-collapse:collapse;
      font-size:13px;
    }
    th,td{
      padding:6px 4px;
      text-align:left;
      border-bottom:1px solid rgba(30,64,175,.5);
    }
    th{font-weight:600;color:#9ca3af;}
    .status-badge{
      padding:2px 8px;
      border-radius:999px;
      font-size:11px;
      text-transform:capitalize;
      display:inline-block;
    }
    .status-pending{background:rgba(234,179,8,.15);color:#facc15;}
    .status-approved{background:rgba(34,197,94,.18);color:#4ade80;}
    .status-rejected{background:rgba(239,68,68,.18);color:#fca5a5;}
    .inline-form{
      display:inline;
    }
    .btn-small{
      border-radius:999px;
      border:none;
      padding:4px 10px;
      font-size:11px;
      cursor:pointer;
      margin-right:4px;
    }
    .btn-approve{
      background:rgba(34,197,94,.2);
      color:#bbf7d0;
      border:1px solid rgba(34,197,94,.6);
    }
    .btn-reject{
      background:rgba(239,68,68,.15);
      color:#fecaca;
      border:1px solid rgba(239,68,68,.6);
    }
    .top-link{margin-bottom:14px;font-size:13px;}
  </style>
</head>
<body>
  <main class="wrap">
    <div class="top-link">
      <a href="admin_dashboard.php">← Back to Admin Dashboard</a>
    </div>

    <h1>Manage Users</h1>

    <?php if (!empty($msg)): ?>
      <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <section class="card">
      <h2>Pending Approvals</h2>
      <?php if (empty($pendingUsers)): ?>
        <p style="font-size:13px;color:#9ca3af;">No pending users.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Joined</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pendingUsers as $u): ?>
              <tr>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($u['role'])); ?></td>
                <td>
                  <span class="status-badge status-<?php echo htmlspecialchars($u['status']); ?>">
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

    <section class="card">
      <h2>All Users (Last 50)</h2>
      <?php if (empty($allUsers)): ?>
        <p style="font-size:13px;color:#9ca3af;">No users found.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Joined</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($allUsers as $u): ?>
              <tr>
                <td><?php echo htmlspecialchars($u['name']); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($u['role'])); ?></td>
                <td>
                  <span class="status-badge status-<?php echo htmlspecialchars($u['status']); ?>">
                    <?php echo htmlspecialchars($u['status']); ?>
                  </span>
                </td>
                <td><?php echo htmlspecialchars($u['created_at']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
