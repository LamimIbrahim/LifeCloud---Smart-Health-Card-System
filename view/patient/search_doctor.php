<?php

$q            = $q            ?? '';
$speciality   = $speciality   ?? '';
$doctors      = $doctors      ?? [];
$specialities = $specialities ?? [];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Search Doctors</title>
    <link rel="stylesheet" href="../Style.css">
    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #020617;
            color: #e5e7eb;
        }
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 24px 40px;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .top-bar h1 {
            margin: 0;
            font-size: 26px;
        }
        .back-link {
            color: #38bdf8;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .card {
            margin-top: 12px;
            background: #020617;
            border-radius: 14px;
            border: 1px solid rgba(148,163,184,.5);
            box-shadow: 0 16px 40px rgba(0,0,0,.35);
            padding: 18px 20px 22px;
        }
        .card h3 {
            margin: 0 0 16px;
            font-size: 18px;
        }
        form.search-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .search-input,
        .select-input {
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #1f2937;
            background: #020617;
            color: #e5e7eb;
            font-size: 14px;
        }
        .search-input::placeholder {
            color: #6b7280;
        }
        .select-input {
            min-width: 180px;
        }
        .btn-primary {
            padding: 8px 16px;
            border-radius: 999px;
            border: none;
            background: #2563eb;
            color: #e5e7eb;
            font-size: 14px;
            cursor: pointer;
            transition: background .15s ease, transform .08s ease;
        }
        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 14px;
        }
        th, td {
            padding: 10px 8px;
            text-align: left;
        }
        thead th {
            border-bottom: 1px solid #1f2937;
            font-weight: 600;
        }
        tbody tr:nth-child(even) {
            background: rgba(15,23,42,.65);
        }
        tbody tr:hover {
            background: rgba(37,99,235,.25);
        }
        .empty-text {
            margin-top: 10px;
            font-size: 14px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="top-bar">
        <h1>Search Doctors</h1>
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>

    <div class="card">
        <h3>Find a specialist</h3>

        <form method="get" action="patient_search_doctor.php" class="search-form">
            <input
                type="text"
                name="q"
                class="search-input"
                placeholder="Search by name or email"
                value="<?php echo htmlspecialchars($q); ?>"
            >

            <select name="speciality" class="select-input">
                <option value="">All Specialities</option>
                <?php foreach ($specialities as $sp): ?>
                    <?php $val = $sp['speciality'] ?? ''; ?>
                    <option
                        value="<?php echo htmlspecialchars($val); ?>"
                        <?php if ($speciality === $val) echo 'selected'; ?>
                    >
                        <?php echo htmlspecialchars($val); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-primary">Search</button>
        </form>

        <?php if (empty($doctors)): ?>
            <p class="empty-text">No doctors found.</p>
        <?php else: ?>
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Speciality</th>
                    <th>Room</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($doctors as $d): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($d['name']       ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['speciality'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['room_no']    ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['phone']      ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($d['email']      ?? ''); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
