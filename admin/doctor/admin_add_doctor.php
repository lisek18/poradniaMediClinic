<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: admin_login.php");
  exit;
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $specialization = trim($_POST['specialization']);
  $department = trim($_POST['department']);
  $biography = trim($_POST['biography']);
  $qualifications = trim($_POST['qualifications']);

  // Walidacja pliku
  if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $errors[] = "Zdjęcie lekarza jest wymagane.";
  } else {
    $image_path = basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
  }

  if (!$name || !$specialization || !$department || !$biography || !$qualifications) {
    $errors[] = "Wszystkie pola formularza są wymagane.";
  }

  if (empty($errors)) {
    $stmt = $mysqli->prepare("INSERT INTO doctors (name, specialization, department, biography, qualifications, image_path)
                              VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $specialization, $department, $biography, $qualifications, $image_path);
    if ($stmt->execute()) {
      $success = "Lekarz został dodany.";
    } else {
      $errors[] = "Błąd przy dodawaniu lekarza.";
    }
  }
}

$departments = $mysqli->query("SELECT name FROM departments");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dodaj lekarza – Panel administratora</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    .form-section {
      max-width: 900px;
      margin: auto;
      background: #f9f9f9;
      padding: 2.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .form-section h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #0a9396;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem 2rem;
    }
    .form-grid label {
      display: flex;
      flex-direction: column;
      font-weight: 600;
      color: #333;
    }
    .form-grid input,
    .form-grid select,
    .form-grid textarea {
      padding: 0.75rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 0.5rem;
    }
    .form-grid textarea {
      resize: vertical;
    }
    .form-grid .full {
      grid-column: 1 / -1;
    }
    .btn-center {
      text-align: center;
      grid-column: 1 / -1;
    }
  </style>
   <link rel="icon" type="image/png" href="image/favicon.png" />
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

  <div class="form-section">
    <h2>Dodaj nowego lekarza</h2>

    <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
    <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

    <form method="post" enctype="multipart/form-data" class="form-grid">
      <label>Imię i nazwisko
        <input type="text" name="name" required>
      </label>
      <label>Specjalizacja
        <input type="text" name="specialization" required>
      </label>
      <label>Dział
        <select name="department" required>
          <option value="">-- wybierz --</option>
          <?php while ($row = $departments->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </label>
      <label>Zdjęcie
        <input type="file" name="image" accept="image/*" required>
      </label>
      <label class="full">Biografia
        <textarea name="biography" rows="4" required></textarea>
      </label>
      <label class="full">Kwalifikacje
        <textarea name="qualifications" rows="4" required></textarea>
      </label>
      <div class="btn-center">
        <button type="submit" class="btn">Dodaj lekarza</button>
      </div>
    </form>
    <div class="btn-center" style="margin-top: 2rem;">
    <a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
    </div>
  </div>

</body>
</html>
