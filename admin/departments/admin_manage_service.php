
<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: admin_login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Zarządzanie usługami – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  
  <style>
    .doctor-admin-container {
      max-width: 900px;
      margin: auto;
      padding: 3rem 1rem;
      text-align: center;
    }
    .doctor-admin-container h2 {
      color: #0a9396;
      margin-bottom: 2rem;
    }
    .admin-buttons {
      display: flex;
      justify-content: center;
      gap: 2rem;
      flex-wrap: wrap;
    }
    .admin-buttons a {
      background: #0a9396;
      color: white;
      padding: 1rem 2rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .admin-buttons a:hover {
      background: #007f7f;
    }
    .btn-center {
      text-align: center;
      grid-column: 1 / -1;
      margin-bottom:50px;
    }
  </style>
   <link rel="icon" type="image/png" href="image/favicon.png" />
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<div class="doctor-admin-container">
  <h2>Zarządzanie usługami</h2>
  <div class="admin-buttons">
    <a href="/poradnia/admin/departments/admin_add_department.php">➕ Dodaj specjalizacje</a>
    <a href="/poradnia/admin/departments/admin_add_service.php">➕ Dodaj usługę</a>
    <a href="/poradnia/admin/departments/admin_manage_services.php">🔎 Zarządzaj pacjentami</a>
  </div>
</div>
<div class="btn-center" style="margin-top: 2rem;">
<a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
    </div>
  
</body>
</html>
