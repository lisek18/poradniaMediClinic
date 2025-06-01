<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: admin_login.php");
  exit;
}

$mysqli = new mysqli("localhost", "root", "", "mediclinic");

// Główne statystyki
$patients = $mysqli->query("SELECT COUNT(*) as count FROM patients")->fetch_assoc()['count'];
$doctors = $mysqli->query("SELECT COUNT(*) as count FROM doctors")->fetch_assoc()['count'];
$departments = $mysqli->query("SELECT COUNT(*) as count FROM departments")->fetch_assoc()['count'];

// Wizyty
$visits_total = $mysqli->query("SELECT COUNT(*) as count FROM visits")->fetch_assoc()['count'];

$visits_this_month = $mysqli->query("
  SELECT COUNT(*) as count 
  FROM visits 
  WHERE MONTH(visit_datetime) = MONTH(CURDATE()) 
    AND YEAR(visit_datetime) = YEAR(CURDATE())
")->fetch_assoc()['count'];

$visits_last_month = $mysqli->query("
  SELECT COUNT(*) as count 
  FROM visits 
  WHERE MONTH(visit_datetime) = MONTH(CURDATE() - INTERVAL 1 MONTH) 
    AND YEAR(visit_datetime) = YEAR(CURDATE() - INTERVAL 1 MONTH)
")->fetch_assoc()['count'];

$avg_visits_per_day = $mysqli->query("
  SELECT ROUND(COUNT(*) / COUNT(DISTINCT DATE(visit_datetime)), 1) as avg 
  FROM visits
")->fetch_assoc()['avg'];

// Statystyki ankiet
$survey_count = $mysqli->query("SELECT COUNT(*) as count FROM patient_satisfaction_surveys")->fetch_assoc()['count'];
$avg_rating = $mysqli->query("SELECT ROUND(AVG(rating), 2) as avg FROM patient_satisfaction_surveys")->fetch_assoc()['avg'] ?? 0;


// Średnia pacjentów na lekarza
$patients_per_doctor = $doctors > 0 ? round($patients / $doctors, 1) : 0;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Statystyki – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <style>
    .admin-panel {
      max-width: 1100px;
      margin: 4rem auto;
      padding: 2rem;
    }
    .admin-panel h2 {
      color: #0a9396;
      text-align: center;
      margin-bottom: 2rem;
    }
    .admin-stats {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      justify-content: center;
      margin-bottom: 3rem;
    }
    .stat-box {
      background: #f1f5f9;
      padding: 2rem;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.04);
      flex: 1 1 240px;
    }
    .stat-box h3 {
      font-size: 1.2rem;
      color: #005f73;
    }
    .stat-box p {
      font-size: 2rem;
      font-weight: bold;
      margin: 0.5rem 0 0;
    }
  </style>
</head>
<body>

<?php include 'includes/admin_header.php'; ?>

<div class="admin-panel">
  <h2>Statystyki zarządzania zasobami</h2>
<form method="POST" action="generate_report.php" style="text-align:right; margin-bottom:1rem;">
  <button type="submit" class="btn">📄 Pobierz raport CSV</button>
</form>

  <div class="admin-stats">
    <div class="stat-box">
      <h3>Pacjenci</h3>
      <p><?= $patients ?></p>
    </div>
    <div class="stat-box">
      <h3>Lekarze</h3>
      <p><?= $doctors ?></p>
    </div>
    <div class="stat-box">
      <h3>Specjalizacje</h3>
      <p><?= $departments ?></p>
    </div>
    <div class="stat-box">
      <h3>Wizyty w bieżący miesiącu</h3>
      <p><?= $visits_this_month ?></p>
    </div>
    <div class="stat-box">
      <h3>Wizyty w poprzednim miesiącu</h3>
      <p><?= $visits_last_month ?></p>
    </div>
    <div class="stat-box">
      <h3>Średnia wizyt dzienna</h3>
      <p><?= $avg_visits_per_day ?></p>
    </div>
    <div class="stat-box">
      <h3>Pacjenci na jednego lekarza</h3>
      <p><?= $patients_per_doctor ?></p>
    </div>
    <div class="stat-box">
  <h3>Wypełnione ankiety</h3>
  <p><?= $survey_count ?></p>
</div>

<div class="stat-box">
  <h3>Średnia ocena z ankiet</h3>
  <p><?= $avg_rating ?>/5.00</p>
</div>

  </div>
</div>

</body>
</html>
