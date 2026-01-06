<?php
// $history asche PatientController::history() theke
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>LifeCloud | Medical History</title>
  <style>
    body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",sans-serif;background:#020617;color:#e5e7eb;}
    .wrap{max-width:1100px;margin:0 auto;padding:24px 16px 40px;}
    h1{margin:0 0 12px;font-size:22px;}
    a{color:#93c5fd;text-decoration:none;}
    a:hover{text-decoration:underline;}
    .card{background:#020617;border-radius:16px;border:1px solid rgba(148,163,184,.4);padding:18px 20px;box-shadow:0 16px 40px rgba(0,0,0,.35);}
    table{width:100%;border-collapse:collapse;font-size:14px;}
    th,td{padding:6px 4px;text-align:left;border-bottom:1px solid rgba(30,64,175,.6);}
    th{font-weight:600;color:#9ca3af;}
    .badge{padding:2px 8px;border-radius:999px;font-size:11px;text-transform:capitalize;display:inline-block;}
    .badge-prescription{background:rgba(34,197,94,.2);color:#bbf7d0;}
    .badge-test{background:rgba(59,130,246,.2);color:#bfdbfe;}
    .badge-appointment{background:rgba(234,179,8,.2);color:#facc15;}
  </style>
</head>
<body>
  <main class="wrap">
    <p><a href="dashboard.php">← Back to Dashboard</a></p>
    <h1>Medical History</h1>

    <section class="card">
      <?php if (empty($history)): ?>
        <p style="font-size:13px;color:#9ca3af;">No medical history found.</p>
      <?php else: ?>
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Title</th>
              <th>Doctor</th>
              <th>Notes / Report</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($history as $row): ?>
              <tr>
                <td><?php echo htmlspecialchars($row['visit_date']); ?></td>
                <td>
                  <?php
                    $type = $row['entry_type'];
                    $class = 'badge';
                    if ($type === 'prescription') $class .= ' badge-prescription';
                    elseif ($type === 'test')     $class .= ' badge-test';
                    elseif ($type === 'appointment') $class .= ' badge-appointment';
                  ?>
                  <span class="<?php echo $class; ?>">
                    <?php echo htmlspecialchars($type); ?>
                  </span>
                </td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['doctor_name'] ?? ''); ?></td>
                <td>
                  <?php if (!empty($row['notes'])): ?>
                    <?php echo nl2br(htmlspecialchars($row['notes'])); ?>
                  <?php endif; ?>
                  <?php if (!empty($row['report_file'])): ?>
                    <br>
                    <a href="<?php echo htmlspecialchars($row['report_file']); ?>" target="_blank">View Report</a>
                  <?php endif; ?>
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
