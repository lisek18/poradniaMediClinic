<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];
// Pobierz imię i nazwisko pacjenta
$result = $mysqli->query("SELECT first_name, last_name FROM patients WHERE id = $patient_id");
$data = $result ? $result->fetch_assoc() : ['first_name' => 'Pacjent', 'last_name' => ''];


// Obsługa przesłania pliku
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
    $file = $_FILES['document'];
    $upload_dir = __DIR__ . '/../uploads/patient_' . $patient_id . '/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = basename($file['name']);
    $relative_path = '/poradnia/uploads/patient_' . $patient_id . '/' . $file_name;
    $target_path = $upload_dir . $file_name;
    $file_type = mime_content_type($file['tmp_name']);

    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $stmt = $mysqli->prepare("INSERT INTO medical_documents (patient_id, file_name, file_path, file_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $patient_id, $file_name, $relative_path, $file_type);
        $stmt->execute();
        $success = true;
    } else {
        $error = "Nie udało się wgrać pliku.";
    }
} // ← tutaj dodałem brakujące zamknięcie if
?>



<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Wgraj dokumenty – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="max-width:1100px; margin-top: 6rem; margin-bottom: 6rem;">
  <div class="profile-header">
    <h2>Twój profil</h2>
    <p><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></p>
  </div>

  <div class="profile-wrapper">
    <div class="profile-sidebar">
      <h3>Panel Pacjenta</h3>
      <?php include 'sidebar.php'; ?>
    </div>

    <div class="profile-main">
      <h3>Dodaj badanie do profilu</h3>

      <?php if (!empty($success)): ?>
        <p style="color: green;">Plik został zapisany poprawnie!</p>
      <?php elseif (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="form-grid">
        <label style="grid-column: 1 / -1;">
          Wybierz plik badania (.PDF, .JPG, .PNG):
          <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required>
        </label>
        <div class="register-submit" style="grid-column: 1 / -1;">
          <button type="submit">Wyślij</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
