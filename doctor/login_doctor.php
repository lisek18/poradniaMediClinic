<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (isset($_SESSION['doctor_logged_in']) && $_SESSION['doctor_logged_in'] === true) {
  header("Location: /poradnia/doctor/panel/panel.php");
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $stmt = $mysqli->prepare("
    SELECT d.id, l.password 
    FROM doctors d 
    JOIN doctor_logins l ON d.id = l.doctor_id 
    WHERE l.email = ?
  ");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($doctor_id, $hashed);
    $stmt->fetch();

    if (password_verify($password, $hashed)) {
      $_SESSION['doctor_logged_in'] = true;
      $_SESSION['doctor_id'] = $doctor_id;
      setcookie("doctor_email", $email, time() + 3600, "/");
      header("Location: /poradnia/doctor/panel/panel.php");
      exit;
    } else {
      $errors[] = "Nieprawidłowe hasło.";
    }
  } else {
    $errors[] = "Nie znaleziono lekarza o tym adresie e-mail.";
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Logowanie lekarza – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
</head>
<body>
  <div class="container" style="max-width:500px; margin:auto; padding:4rem 1rem;">
    <h2 style="text-align:center; color:#0a9396;">Logowanie lekarza</h2>
    <?php if (!empty($errors)) {
      echo "<ul style='color:red;'>";
      foreach ($errors as $e) echo "<li>$e</li>";
      echo "</ul>";
    } ?>
    <form method="post" style="display:flex; flex-direction:column; gap:1rem;">
      <label>Email:
        <input type="email" name="email" required>
      </label>
      <label>Hasło:
        <input type="password" name="password" required>
      </label>
      <button type="submit" class="btn">Zaloguj</button>
    </form>
  </div>
</body>
</html>
