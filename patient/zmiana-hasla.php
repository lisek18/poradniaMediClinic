
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$errors = [];
$success = '';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT first_name, last_name FROM patients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($first, $last);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current = $_POST['current_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  if (!$current || !$new || !$confirm) {
    $errors[] = "Wszystkie pola są wymagane.";
  } elseif ($new !== $confirm) {
    $errors[] = "Nowe hasła się nie zgadzają.";
  } elseif (strlen($new) < 6) {
    $errors[] = "Nowe hasło musi mieć co najmniej 6 znaków.";
  } elseif (stripos($new, $first) !== false || stripos($new, $last) !== false) {
    $errors[] = "Hasło nie może zawierać imienia ani nazwiska.";
  } else {
    $stmt = $mysqli->prepare("SELECT password FROM patients WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($hashed);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current, $hashed)) {
      $errors[] = "Obecne hasło jest nieprawidłowe.";
    } else {
      $new_hashed = password_hash($new, PASSWORD_DEFAULT);
      $update = $mysqli->prepare("UPDATE patients SET password = ? WHERE id = ?");
      $update->bind_param("si", $new_hashed, $id);
      if ($update->execute()) {
        $success = "Hasło zostało zmienione.";
      } else {
        $errors[] = "Błąd przy aktualizacji hasła.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Zmiana hasła – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
</head>
<body>
<?php include '../includes/header.php'; ?>


<section class="section">
  <div class="container" style="max-width:1100px;">
    <div class="profile-header">
      <h2>Twój profil</h2>
      <p><?= htmlspecialchars($first . ' ' . $last) ?></p>
    </div>
    <div class="profile-wrapper">
      <div class="profile-sidebar">
        <h3>Panel pacjenta</h3>
        <?php include 'sidebar.php'; ?>
      </div>

      <div class="profile-main">
        <h3>Zmień hasło</h3><br>
        <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
        <?php if ($success) { echo "<p style='color:green;'>$success</p>"; } ?>
        <form method="post" class="form-grid" style="max-width:700px;">
          <label>Obecne hasło:<input type="password" name="current_password" required></label>
          <label>Nowe hasło:<input type="password" name="new_password" required></label>
          <label>Powtórz nowe hasło:<input type="password" name="confirm_password" required></label>
          <div style="grid-column: 1 / -1; text-align:center;">
            <button type="submit" class="btn">Zmień hasło</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="/poradnia/script-fixed.js"></script>
</body>
</html>
