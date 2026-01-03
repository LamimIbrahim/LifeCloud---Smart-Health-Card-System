<?php
session_start();
include 'db_connect.php';

/* 1) Must be logged in */
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

/* 2) Search input (GET) */
$q = trim($_GET['q'] ?? '');  // health card id or patient name

/* 3) Recent patients (latest 5) */
$recent = [];
$r = $conn->query("SELECT id, full_name, health_card_id FROM patients ORDER BY id DESC LIMIT 5");
if ($r) { $recent = $r->fetch_all(MYSQLI_ASSOC); }

/* 4) Find patient */
$patient = null;
$history = [];

if ($q !== '') {
  $stmt = $conn->prepare("SELECT * FROM patients WHERE health_card_id = ? OR full_name LIKE CONCAT('%', ?, '%') LIMIT 1");
  $stmt->bind_param("ss", $q, $q);
  $stmt->execute();
  $res = $stmt->get_result();
  $patient = $res->fetch_assoc();
  $stmt->close();

  if ($patient) {
    $stmt2 = $conn->prepare("SELECT title, visit_date, doctor_name FROM medical_history WHERE patient_id=? ORDER BY visit_date DESC, id DESC LIMIT 5");
    $stmt2->bind_param("i", $patient['id']);
    $stmt2->execute();
    $history = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Doctor's Portal</title>
  <link rel="stylesheet" href="doctor_portal.css" />
</head>
<body>
  <div class="doc-wrap">
    <header class="doc-top">
      <div class="brand">
        <img class="brand-logo" src="logo.png" alt="LifeCloud logo">
        <div class="brand-text">
          <h1>LifeCloud</h1>
          <p>Health on Cloud</p>
        </div>
      </div>
    </header>

    <main class="doc-grid">
      <!-- LEFT -->
      <aside class="left">
        <div class="page-title">
          <span class="steth">🩺</span>
          <h2>Doctor’s Portal</h2>
        </div>

        <section class="card">
          <div class="card-h">
            <span class="ic">🔎</span>
            <h3>Patient Search</h3>
          </div>

<form class="search" method="GET" action="" autocomplete="off">
  <label for="q">Health Card ID</label>
  
  <input
    id="q"
    name="healthcard_search"
    type="search"
    autocomplete="off"
    autocapitalize="off"
    autocorrect="off"
    spellcheck="false"
    data-lpignore="true"
    data-form-type="other"
    placeholder="HC-2024-001234 or Name"
    value="<?php echo htmlspecialchars($q); ?>"
  />
  
  <button class="btn" type="submit">Search</button>
</form>



          <div class="subhead">Recent Patient</div>
          <div class="mini-list">
            <?php foreach ($recent as $p): ?>
              <a class="mini" href="?q=<?php echo urlencode($p['health_card_id']); ?>">
                <div class="mini-name"><?php echo htmlspecialchars($p['full_name']); ?></div>
                <div class="mini-id"><?php echo htmlspecialchars($p['health_card_id']); ?></div>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      </aside>

      <!-- RIGHT -->
      <section class="right">
        <section class="card">
          <div class="card-h">
            <span class="ic">👤</span>
            <h3>Patient Details</h3>
          </div>

          <?php if ($patient): ?>
            <div class="details-grid">
              <div class="detail"><div class="k">Name</div><div class="v"><?php echo htmlspecialchars($patient['full_name']); ?></div></div>
              <div class="detail"><div class="k">Health Card Id</div><div class="v"><?php echo htmlspecialchars($patient['health_card_id']); ?></div></div>
              <div class="detail"><div class="k">Date of Birth</div><div class="v"><?php echo htmlspecialchars($patient['dob'] ?? '—'); ?></div></div>
              <div class="detail"><div class="k">Email</div><div class="v"><?php echo htmlspecialchars($patient['email'] ?? '—'); ?></div></div>
              <div class="detail"><div class="k">Contact</div><div class="v"><?php echo htmlspecialchars($patient['phone'] ?? '—'); ?></div></div>
              <div class="detail"><div class="k">Address</div><div class="v"><?php echo htmlspecialchars($patient['address'] ?? '—'); ?></div></div>
            </div>
          <?php else: ?>
            <p class="empty">Search করে patient select করো।</p>
          <?php endif; ?>
        </section>

        <section class="card">
          <div class="card-h">
            <span class="ic">🧾</span>
            <h3>Medical History</h3>
          </div>

          <?php if ($patient && count($history)): ?>
            <div class="history">
              <?php foreach ($history as $h): ?>
                <div class="history-item">
                  <div class="hi-title"><?php echo htmlspecialchars($h['title']); ?></div>
                  <div class="hi-sub">
                    <?php echo htmlspecialchars($h['visit_date'] ?? ''); ?>
                    <?php echo ($h['doctor_name'] ? " · ".htmlspecialchars($h['doctor_name']) : ""); ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="empty">No medical history.</p>
          <?php endif; ?>
        </section>

        <section class="card">
          <div class="actions">
            <button class="action" type="button">Add Prescription</button>
            <button class="action" type="button">Upload Report</button>
            <button class="action" type="button">Schedule Appointment</button>
            <button class="action" type="button">View Full Report</button>
          </div>
        </section>
      </section>
    </main>
  </div>
</body>
</html>
