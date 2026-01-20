<?php

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Doctor's Portal</title>
  <style>
    *,
    *::before,
    *::after { box-sizing:border-box; }

    body {
      margin:0;
      font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
      background:#0b1120;
      color:#e5e7eb;
    }
    .doc-wrap{min-height:100vh;padding:24px 16px 32px;}
    .doc-top{
      max-width:1100px;margin:0 auto 18px;
      display:flex;align-items:center;justify-content:space-between;
    }
    .brand{display:flex;align-items:center;gap:10px;}
    .brand-text h1{margin:0;font-size:22px;}
    .brand-text p{margin:0;font-size:12px;color:#9ca3af;}
    .logout-btn{
      border-radius:999px;padding:8px 14px;
      border:1px solid rgba(148,163,184,.7);
      background:#020617;color:#e5e7eb;
      font-size:13px;font-weight:600;text-decoration:none;
      display:inline-flex;align-items:center;gap:6px;
    }
    .logout-btn:hover{border-color:#f97373;color:#fecaca;}

    .doc-grid{
      max-width:1100px;margin:0 auto;
      display:grid;grid-template-columns:320px 1fr;gap:22px;
    }
    .card{
      background:#020617;border-radius:14px;
      border:1px solid rgba(148,163,184,.25);
      padding:18px 18px 20px;
      box-shadow:0 16px 40px rgba(0,0,0,.35);
    }
    .card-h{display:flex;align-items:center;gap:8px;margin-bottom:14px;}
    .card-h h3{margin:0;font-size:16px;}

    .left .page-title{display:flex;align-items:center;gap:8px;margin-bottom:12px;}

    .search{display:flex;flex-direction:column;gap:8px;margin-bottom:16px;}
    .search label{font-size:13px;color:#9ca3af;}
    .search input{
      padding:9px 10px;border-radius:8px;border:1px solid #1f2937;
      background:#020617;color:#e5e7eb;
    }
    .search .btn{
      margin-top:6px;padding:8px 12px;border-radius:999px;
      background:linear-gradient(135deg,#0ea5e9,#22c55e);
      border:none;font-weight:600;cursor:pointer;
    }

    .subhead{margin:4px 0 8px;font-size:13px;color:#9ca3af;}
    .mini-list{display:flex;flex-direction:column;gap:6px;}
    .mini{
      display:flex;justify-content:space-between;
      padding:8px 10px;border-radius:8px;
      text-decoration:none;color:#e5e7eb;
      background:#020617;border:1px solid rgba(148,163,184,.5);
      font-size:13px;
    }
    .mini:hover{border-color:#22c55e;}

    .details-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px 14px;}
    .detail .k{font-size:11px;color:#9ca3af;margin-bottom:2px;}
    .detail .v{font-size:14px;}
    .empty{font-size:13px;color:#9ca3af;}

    .history{display:flex;flex-direction:column;gap:10px;}
    .history-item{
      padding:8px 10px;border-radius:10px;
      background:#020617;border:1px solid rgba(148,163,184,.4);
    }
    .hi-title{font-size:14px;margin-bottom:2px;}
    .hi-sub{font-size:11px;color:#9ca3af;}

    .actions{display:flex;flex-wrap:wrap;gap:10px;}
    .action{
      flex:1;min-width:140px;padding:10px 12px;border-radius:10px;
      border:1px solid rgba(148,163,184,.5);background:#020617;
      color:#e5e7eb;cursor:pointer;font-size:13px;font-weight:600;
    }
    .action:hover{border-color:#0ea5e9;}

    .small-form{margin-top:12px;display:flex;flex-direction:column;gap:8px;}
    .small-form input,.small-form textarea{
      width:100%;padding:8px 9px;border-radius:8px;
      border:1px solid #1f2937;background:#020617;color:#e5e7eb;font-size:13px;
    }
    .small-form textarea{min-height:70px;resize:vertical;}
    .small-form label{font-size:12px;color:#9ca3af;}
    .msg-action{margin-top:8px;font-size:13px;color:#bbf7d0;}

    .full-history-item{border-bottom:1px solid rgba(15,23,42,.9);padding:8px 0;font-size:13px;}
    .full-history-item:last-child{border-bottom:none;}
    .full-history-title{font-weight:600;}
    .full-history-meta{font-size:11px;color:#9ca3af;}
    .full-history-notes{margin-top:2px;}
    .full-history-report a{color:#38bdf8;font-size:12px;}

    @media(max-width:900px){.doc-grid{grid-template-columns:1fr;}}
  </style>
</head>
<body>
  <div class="doc-wrap">
    <header class="doc-top">
      <div class="brand">
        <div class="brand-text">
          <h1>LifeCloud</h1>
          <p>Doctor's Portal</p>
        </div>
      </div>
      <a class="logout-btn" href="logout.php">🚪 Logout</a>
    </header>

    <main class="doc-grid">
      <!-- LEFT -->
      <aside class="left">
        <div class="page-title">
          <span>🩺</span>
          <h2>Welcome, Dr. <?php echo htmlspecialchars($doctorName); ?></h2>
        </div>

        <section class="card">
          <div class="card-h">
            <span>🔎</span>
            <h3>Patient Search</h3>
          </div>

          <form class="search" method="GET" action="" autocomplete="off">
            <label for="q">Health Card No or Name</label>
            <input id="q" name="q" type="search" placeholder="LC2601050001 or Name"
                   value="<?php echo htmlspecialchars($q); ?>" />
            <button class="btn" type="submit">Search</button>
          </form>

          <div class="subhead">Recent Patient</div>
          <div class="mini-list">
            <?php foreach ($recent as $p): ?>
              <a class="mini" href="?q=<?php echo urlencode($p['health_card_no']); ?>">
                <div><?php echo htmlspecialchars($p['name']); ?></div>
                <div style="color:#9ca3af;"><?php echo htmlspecialchars($p['health_card_no']); ?></div>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      </aside>

      <!-- RIGHT -->
      <section class="right">
        <section class="card">
          <div class="card-h">
            <span>👤</span>
            <h3>Patient Details</h3>
          </div>

          <?php if ($patient): ?>
            <div class="details-grid">
              <div class="detail">
                <div class="k">Name</div>
                <div class="v"><?php echo htmlspecialchars($patient['name']); ?></div>
              </div>
              <div class="detail">
                <div class="k">Health Card No</div>
                <div class="v"><?php echo htmlspecialchars($patient['health_card_no']); ?></div>
              </div>
              <div class="detail">
                <div class="k">Date of Birth</div>
                <div class="v"><?php echo htmlspecialchars($patient['date_of_birth'] ?? '—'); ?></div>
              </div>
              <div class="detail">
                <div class="k">Email</div>
                <div class="v"><?php echo htmlspecialchars($patient['email'] ?? '—'); ?></div>
              </div>
              <div class="detail">
                <div class="k">Gender</div>
                <div class="v"><?php echo htmlspecialchars($patient['gender'] ?? '—'); ?></div>
              </div>
              <div class="detail">
                <div class="k">Address</div>
                <div class="v"><?php echo htmlspecialchars($patient['address'] ?? '—'); ?></div>
              </div>
            </div>
          <?php else: ?>
            <p class="empty">Search to select a patient.</p>
          <?php endif; ?>
        </section>

        <section class="card">
          <div class="card-h">
            <span>🧾</span>
            <h3>Recent Medical History</h3>
          </div>

          <?php if ($patient && count($history)): ?>
            <div class="history">
              <?php foreach ($history as $h): ?>
                <div class="history-item">
                  <div class="hi-title"><?php echo htmlspecialchars($h['title']); ?></div>
                  <div class="hi-sub">
                    <?php echo htmlspecialchars($h['visit_date'] ?? ''); ?>
                    <?php echo !empty($h['doctor_name']) ? " · " . htmlspecialchars($h['doctor_name']) : ""; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="empty">No medical history for this patient.</p>
          <?php endif; ?>

          <?php if ($msg_action): ?>
            <div class="msg-action"><?php echo htmlspecialchars($msg_action); ?></div>
          <?php endif; ?>
        </section>

        <section class="card">
          <div class="actions">
            <button class="action" type="button" onclick="toggleForm('prescription')">Add Prescription</button>
            <button class="action" type="button" onclick="toggleForm('report')">Upload Report</button>
            <button class="action" type="button" onclick="toggleForm('appointment')">Schedule Appointment</button>
            <button class="action" type="button" onclick="toggleForm('full')">View Full Report</button>
          </div>

          <?php if ($patient): ?>
            <!-- Add Prescription -->
            <form class="small-form" id="form-prescription" method="post" style="display:none;">
              <input type="hidden" name="action" value="add_prescription">
              <input type="hidden" name="patient_id" value="<?php echo (int)$patient['patient_id']; ?>">
              <label>Title</label>
              <input type="text" name="title" placeholder="Prescription title" value="Prescription">
              <label>Notes</label>
              <textarea name="notes" placeholder="Medicines, dosage, instructions"></textarea>
              <button class="action" type="submit">Save Prescription</button>
            </form>

            <!-- Upload Report -->
            <form class="small-form" id="form-report" method="post" enctype="multipart/form-data" style="display:none;">
              <input type="hidden" name="action" value="upload_report">
              <input type="hidden" name="patient_id" value="<?php echo (int)$patient['patient_id']; ?>">
              <label>Report Title</label>
              <input type="text" name="title" placeholder="e.g. Blood test report">
              <label>Report File</label>
              <input type="file" name="report_file" accept=".pdf,.png,.jpg,.jpeg">
              <button class="action" type="submit">Upload</button>
            </form>

            <!-- Schedule Appointment -->
            <form class="small-form" id="form-appointment" method="post" style="display:none;">
              <input type="hidden" name="action" value="schedule_appointment">
              <input type="hidden" name="patient_id" value="<?php echo (int)$patient['patient_id']; ?>">
              <label>Title</label>
              <input type="text" name="title" placeholder="Follow-up visit">
              <label>Date</label>
              <input type="date" name="visit_date" value="<?php echo date('Y-m-d'); ?>">
              <button class="action" type="submit">Schedule</button>
            </form>

            <!-- Full history trigger -->
            <form class="small-form" id="form-full" method="post" style="display:none;">
              <input type="hidden" name="action" value="view_full">
              <input type="hidden" name="patient_id" value="<?php echo (int)$patient['patient_id']; ?>">
              <button class="action" type="submit">Load Full History</button>
            </form>
          <?php else: ?>
            <p class="empty" style="margin-top:12px;">Select a patient to use these actions.</p>
          <?php endif; ?>
        </section>

        <?php if ($patient && $full_history): ?>
          <section class="card">
            <div class="card-h">
              <span>📚</span>
              <h3>Full Medical Report</h3>
            </div>
            <?php foreach ($full_history as $fh): ?>
              <div class="full-history-item">
                <div class="full-history-title"><?php echo htmlspecialchars($fh['title']); ?></div>
                <div class="full-history-meta">
                  <?php echo htmlspecialchars($fh['visit_date']); ?>
                  <?php echo !empty($fh['doctor_name']) ? " · " . htmlspecialchars($fh['doctor_name']) : ""; ?>
                </div>
                <?php if (!empty($fh['notes'])): ?>
                  <div class="full-history-notes">
                    <?php echo nl2br(htmlspecialchars($fh['notes'])); ?>
                  </div>
                <?php endif; ?>
                <?php if (!empty($fh['report_file'])): ?>
                  <div class="full-history-report">
                    <a href="<?php echo htmlspecialchars($fh['report_file']); ?>" target="_blank">View Report File</a>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </section>
        <?php endif; ?>
      </section>
    </main>
  </div>

  <script>
    function toggleForm(which){
      const forms=['prescription','report','appointment','full'];
      forms.forEach(f=>{
        const el=document.getElementById('form-'+f);
        if(!el) return;
        if(f===which){
          el.style.display = (el.style.display==='none' || el.style.display==='') ? 'block' : 'none';
        }else{
          el.style.display='none';
        }
      });
    }
  </script>
</body>
</html>