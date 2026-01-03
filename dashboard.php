<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from session or database if needed
$userName = $_SESSION['user_name'] ?? "Micah Bell"; // Default name for demo
$userEmail = "example@email.com"; // You can fetch this from DB
$memberSince = "January 2024";
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LifeCloud | Dashboard</title>
  <link rel="stylesheet" href="style.css" />
  <!-- Simple icons for sidebar -->
  <style>
    /* Inline minimal icons for demo (using emoji/unicode or you can use FontAwesome) */
    .icon { margin-right: 10px; font-size: 1.1em; }
  </style>
</head>
<body>
  <div class="dashboard-container">
    
    <!-- Top Header -->
    <header class="top-bar">
       <div class="brand-centered">
          <div class="logo"><img src="assets/logo.png" alt="LC"></div>
          <div class="brand-text">
            <h1>LifeCloud</h1>
            <p>Health on Cloud</p>
          </div>
       </div>
    </header>

    <main class="dashboard-grid">
      
      <!-- Sidebar / Left Panel -->
      <aside class="sidebar">
        <div class="welcome-box">
           <h2>🏠 Patient Dashboard</h2>
           <p>Welcome back, <?php echo htmlspecialchars($userName); ?></p>
        </div>

        <nav class="nav-menu">
           <h3>Navigation</h3>
           <ul>
             <li><a href="#" class="active"><span class="icon">🏠</span> Home</a></li>
             <li><a href="#"><span class="icon">📅</span> Appointment</a></li>
             <li><a href="#"><span class="icon">📄</span> Prescription</a></li>
             <li><a href="#"><span class="icon">🧪</span> Test Result</a></li>
             <li><a href="logout.php"><span class="icon">⚙️</span> Settings (Logout)</a></li>
           </ul>
        </nav>
      </aside>

      <!-- Main Content Area -->
      <section class="content-area">
        
        <!-- Card 1: Profile Summary -->
        <div class="dash-card">
           <div class="card-header">
             <h3>👤 Profile Summary</h3>
           </div>
           <div class="profile-info">
              <div class="info-item">
                 <small>Name</small>
                 <strong><?php echo htmlspecialchars($userName); ?></strong>
              </div>
              <div class="info-item">
                 <small>Health Card Number</small>
                 <strong>💳 HC-2024-001234</strong>
              </div>
              <div class="info-item">
                 <small>Member Since</small>
                 <strong><?php echo $memberSince; ?></strong>
              </div>
           </div>
        </div>

        <!-- Card 2: Recent Visits -->
        <div class="dash-card">
           <div class="card-header">
             <h3>🕒 Recent Visits</h3>
           </div>
           <div class="visit-list">
              <div class="visit-item">
                 <h4>Dr. Adgar Ross - General Checkup</h4>
                 <span>July 15, 2025</span>
              </div>
              <div class="visit-item">
                 <h4>Dr. Milton - Cardiology</h4>
                 <span>January 15, 2025</span>
              </div>
              <div class="visit-item">
                 <h4>Dr. Trelawny - Dermatology</h4>
                 <span>June 15, 2024</span>
              </div>
           </div>
        </div>

        <!-- Card 3: Quick Action -->
        <div class="dash-card">
           <div class="card-header">
             <h3>⚡ Quick Action</h3>
           </div>
           <div class="action-buttons">
              <button class="action-btn">
                <span class="btn-icon">+</span>
                Book Appointment
              </button>
              <button class="action-btn">
                <span class="btn-icon">🧪</span>
                Test Results
              </button>
              <button class="action-btn">
                <span class="btn-icon">📄</span>
                Medical History
              </button>
           </div>
        </div>

      </section>
    </main>
  </div>
</body>
</html>
