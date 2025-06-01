
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$success = "";
$errors = [];

if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $description = trim($_POST['description']);
  $link = trim($_POST['link']);
  $image_path = "";

  if (!$_FILES['image']['error']) {
    $image_path = basename($_FILES['image']['name']);
    move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $image_path);
  }

  if (!$name || !$description || !$link || !$image_path) {
    $errors[] = "Wszystkie pola są wymagane.";
  } else {
    $stmt = $mysqli->prepare("INSERT INTO departments (name, description, image_path, link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $description, $image_path, $link);
    if ($stmt->execute()) {
      $table_name = strtolower($name) . "_services";
      $mysqli->query("CREATE TABLE IF NOT EXISTS `$table_name` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        service_name VARCHAR(255),
        price VARCHAR(50)
      )");
      $success = "Dział został dodany.";
    } else {
      $errors[] = "Błąd dodawania działu.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dodaj specjalizację – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" type="image/png" href="image/favicon.png" />
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
    input, textarea {
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
</head>
<body>
<?php include '../includes/admin_header.php'; ?>
<div class="admin-section">
  <h2>Dodaj specjalizację</h2><br><br>
  <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
  <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Nazwa:</label>
    <input type="text" name="name" required>
    <label>Opis:</label>
    <textarea name="description" rows="4" required></textarea>
    <label>Plik obrazka:</label>
    <input type="file" name="image" required>
    <label>Link (np. dermatologia.php):</label>
    <input type="text" name="link" required><br><br>
    <button class="btn" type="submit">Dodaj specjalizację</button>
  </form>
</div>
<div class="btn-center" style="margin-top: 2rem;">
<a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
    </div>
  
</body>
</html>
