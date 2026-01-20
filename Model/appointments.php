<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LifeCloud | Appointments</title>
  <style>
    body{
      margin:0;
      font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
      background:#020617;
      color:#e5e7eb;
    }
    .wrap{
      max-width:900px;
      margin:0 auto;
      padding:24px 16px 32px;
    }
    a{color:#93c5fd;text-decoration:none;}
    a:hover{text-decoration:underline;}
    h1{margin:0 0 16px;font-size:22px;}
    .top-link{margin-bottom:14px;font-size:13px;}
    .card{
      background:#020617;
      border-radius:14px;
      border:1px solid rgba(148,163,184,.4);
      padding:16px 18px 18px;
      box-shadow:0 16px 40px rgba(0,0,0,.35);
      margin-bottom:18px;
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
    .muted{font-size:13px;color:#9ca3af;}
    .badge-role{
      font-size:11px;
      padding:2px 8px;
      border-radius:999px;
      background:rgba(59,130,246,.18);
      color:#bfdbfe;
    }
  </style>
</head>
<body>
<main class="wrap">
  <div class="top-link">
    <a href="dashboard.php">← Back to Dashboard</a>
  </div>

  <h1>Appointments</h1>
  <p class="muted">
    Logged in as <span class="badge-role"><?php echo htmlspecialchars($user['role']); ?></span>
  </p>

  <section class="card">
    <h2 style="margin:0 0 10px;font-size:18px;">Your Appointments (AJAX + JSON)</h2>
    <p id="appt-status" class="muted">Loading appointments...</p>

    <table id="appt-table" style="display:none;">
      <thead>
      <tr>
        <th>Date</th>
        <th>Title</th>
        <?php if ($user['role'] === 'patient'): ?>
          <th>Doctor</th>
        <?php elseif ($user['role'] === 'doctor'): ?>
          <th>Patient</th>
        <?php else: ?>
          <th>Relation</th>
        <?php endif; ?>
      </tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>
</main>

<script>
  // Load appointments via AJAX (JSON)
  function loadAppointments() {
    var status = document.getElementById('appt-status');
    var table  = document.getElementById('appt-table');
    var tbody  = table.querySelector('tbody');

    fetch('api_appointments.php')
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (!data.success) {
          status.textContent = data.message || 'Failed to load appointments.';
          return;
        }

        if (!data.count) {
          status.textContent = 'No appointments found.';
          return;
        }

        status.style.display = 'none';
        table.style.display = 'table';

        data.data.forEach(function (row) {
          var tr = document.createElement('tr');

          var tdDate = document.createElement('td');
          tdDate.textContent = row.date;
          tr.appendChild(tdDate);

          var tdTitle = document.createElement('td');
          tdTitle.textContent = row.title;
          tr.appendChild(tdTitle);

          var tdWho = document.createElement('td');
          // backend different field names
          if (row.doctor_name) {
            tdWho.textContent = row.doctor_name;
          } else if (row.patient_name) {
            tdWho.textContent = row.patient_name;
          } else if (row.relation) {
            tdWho.textContent = row.relation;
          } else {
            tdWho.textContent = '-';
          }
          tr.appendChild(tdWho);

          tbody.appendChild(tr);
        });
      })
      .catch(function () {
        status.textContent = 'Network error while loading appointments.';
      });
  }

  loadAppointments();
</script>
</body>
</html>
