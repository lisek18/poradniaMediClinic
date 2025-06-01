
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$success = "";
$errors = [];

if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

$departments = $mysqli->query("SELECT name FROM departments");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $department = $_POST['department'];
  $service_name = trim($_POST['service_name']);
  $price = trim($_POST['price']);
  $table = strtolower($department) . "_services";

  if ($service_name && $price && $department) {
    $stmt = $mysqli->prepare("INSERT INTO `$table` (service_name, price) VALUES (?, ?)");
    $stmt->bind_param("ss", $service_name, $price);
    if ($stmt->execute()) {
      $success = "Usługa została dodana.";
    } else {
      $errors[] = "Błąd przy dodawaniu badania.";
    }
  } else {
    $errors[] = "Wszystkie pola są wymagane.";
  }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dodaj badanie – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    .admin-section {
      max-width: 1000px;
      margin: auto;
      padding: 3rem 1rem;
    }
    form {
      background: #f9f9f9;
      padding: 1.5rem;
      border-radius: 8px;
      margin-bottom: 2rem;
    }
    h2, h3 {
      text-align: center;
      color: #0a9396;
    }
    label {
      font-weight: bold;
      display: block;
      margin-top: 1rem;
    }
    input, select {
      width: 100%;
      padding: 0.7rem;
      margin-top: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 6px;
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
<div class="admin-section">
  <h2>Dodaj badanie</h2><br><br>
  <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
  <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

  <form method="post">
    <label>Dział:</label>
    <select name="department" required>
      <option value="">-- Wybierz dział --</option>
      <?php while ($row = $departments->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
      <?php endwhile; ?>
    </select>
    <label>Nazwa badania:</label>
    <input type="text" name="service_name" required>
    <label>Cena:</label>
    <input type="text" name="price" required><br><br>
    <button class="btn" type="submit">Dodaj badanie</button>
  </form>
</div>
<div class="btn-center" style="margin-top: 2rem;">
<a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
    </div>

</body>
</html>
