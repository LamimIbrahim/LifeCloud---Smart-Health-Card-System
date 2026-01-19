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
    .msg-error{
      background:rgba(239,68,68,.15);
      border-color:rgba(239,68,68,.6);
      color:#fecaca;
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
    .btn-small[disabled]{
      opacity:.5;
      cursor:not-allowed;
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

    <div id="flash-msg"
         class="msg"
         style="display:<?php echo empty($msg) ? 'none' : 'block'; ?>;">
      <?php echo htmlspecialchars($msg ?? ''); ?>
    </div>

    <section class="card" id="pending">
      <h2>Pending Approvals (AJAX)</h2>
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
            <tr data-user-id="<?php echo (int)$u['id']; ?>">
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
                <button class="btn-small btn-approve js-approve"
                        data-action="approve">
                  Approve
                </button>
                <button class="btn-small btn-reject js-reject"
                        data-action="reject">
                  Reject
                </button>
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

  <script>
    // Simple helper to show flash message (from JSON)
    function showFlash(message, isError) {
      var box = document.getElementById('flash-msg');
      box.textContent = message;
      box.style.display = 'block';
      if (isError) {
        box.classList.add('msg-error');
      } else {
        box.classList.remove('msg-error');
      }
      // auto hide after 3 sec
      setTimeout(function () {
        box.style.display = 'none';
      }, 3000);
    }

    function handleActionClick(event) {
      var btn = event.target;
      if (!btn.dataset.action) return;

      var row = btn.closest('tr');
      var userId = row.getAttribute('data-user-id');
      var action = btn.dataset.action;

      // Disable buttons
      var approveBtn = row.querySelector('.js-approve');
      var rejectBtn = row.querySelector('.js-reject');
      approveBtn.disabled = true;
      rejectBtn.disabled = true;

      var formData = new FormData();
      formData.append('user_id', userId);
      formData.append('action', action);

      fetch('api_admin_users.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin' // IMPORTANT: send PHP session cookie
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (!data.success) {
            showFlash(data.message || 'Something went wrong', true);
            approveBtn.disabled = false;
            rejectBtn.disabled = false;
            return;
          }

          // Update badge/status in row
          var badge = row.querySelector('.status-badge');
          badge.textContent = data.new_status;
          badge.classList.remove('status-pending','status-approved','status-rejected');
          badge.classList.add('status-' + data.new_status);

          // After approve/reject hide row from pending list
          row.parentNode.removeChild(row);

          showFlash(data.message, false);
        })
        .catch(function () {
          showFlash('Network error', true);
          approveBtn.disabled = false;
          rejectBtn.disabled = false;
        });
    }

    // Attach click handlers (event delegation)
    var pendingTable = document.querySelector('#pending tbody');
    if (pendingTable) {
      pendingTable.addEventListener('click', function (e) {
        if (e.target.matches('.js-approve') || e.target.matches('.js-reject')) {
          handleActionClick(e);
        }
      });
    }
  </script>
</body>
</html>
