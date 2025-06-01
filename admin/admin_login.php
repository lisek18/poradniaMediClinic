
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
  header("Location: admin_dashboard.php");
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  $stmt = $mysqli->prepare("SELECT id, password FROM admins WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    $stmt->bind_result($admin_id, $hashed);
    $stmt->fetch();

    if (password_verify($password, $hashed)) {
      $_SESSION['admin_logged_in'] = true;
      $_SESSION['admin_id'] = $admin_id;
      setcookie("admin_email", $email, time() + 3600, "/");
      header("Location: admin_dashboard.php");
      exit;
    } else {
      $errors[] = "Nieprawidłowe hasło.";
    }
  } else {
    $errors[] = "Nie znaleziono administratora.";
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Logowanie administratora – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
</head>
<body>
  <div class="container" style="max-width:500px; margin:auto; padding:4rem 1rem;">
    <h2 style="text-align:center; color:#0a9396;">Logowanie administratora</h2>
    <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
    <form method="post" style="display:flex; flex-direction:column; gap:1rem;">
      <label>Email:<input type="email" name="email" required></label>
      <label>Hasło:<input type="password" name="password" required></label>
      <button type="submit" class="btn">Zaloguj</button>
    </form>
  </div>
</body>
</html>
