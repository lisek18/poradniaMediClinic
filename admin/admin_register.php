
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];

  if (!$email || !$password || !$confirm) {
    $errors[] = "Wszystkie pola są wymagane.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Nieprawidłowy adres e-mail.";
  } elseif ($password !== $confirm) {
    $errors[] = "Hasła się nie zgadzają.";
  } elseif (strlen($password) < 6) {
    $errors[] = "Hasło musi mieć co najmniej 6 znaków.";
  } else {
    $check = $mysqli->prepare("SELECT id FROM admins WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
      $errors[] = "Taki adres e-mail już istnieje.";
    } else {
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $mysqli->prepare("INSERT INTO admins (email, password) VALUES (?, ?)");
      $stmt->bind_param("ss", $email, $hashed);
      if ($stmt->execute()) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $stmt->insert_id;
        setcookie("admin_email", $email, time() + 3600, "/");
        header("Location: admin_dashboard.php");
        exit;
      } else {
        $errors[] = "Błąd przy tworzeniu konta.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Rejestracja administratora – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
</head>
<body>
  <div class="container" style="max-width:500px; margin:auto; padding:4rem 1rem;">
    <h2 style="text-align:center; color:#0a9396;">Rejestracja administratora</h2>
    <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
    <form method="post" style="display:flex; flex-direction:column; gap:1rem;">
      <label>Email:<input type="email" name="email" required></label>
      <label>Hasło:<input type="password" name="password" required></label>
      <label>Powtórz hasło:<input type="password" name="confirm_password" required></label>
      <button type="submit" class="btn">Zarejestruj</button>
    </form>
  </div>
</body>
</html>
