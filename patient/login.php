
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Nieprawidłowy adres e-mail.";
  } elseif (!$password) {
    $errors[] = "Wprowadź hasło.";
  } else {
    $stmt = $mysqli->prepare("SELECT id, password FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 1) {
      $stmt->bind_result($id, $hashed);
      $stmt->fetch();
      if (password_verify($password, $hashed)) {
        $_SESSION['user_id'] = $id;
        setcookie("user_email", $email, time() + 3600, "/");
        header("Location: profil.php");
        exit;
      } else {
        $errors[] = "Nieprawidłowe hasło.";
      }
    } else {
      $errors[] = "Użytkownik nie istnieje.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Logowanie – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />

  <style>
    .form-login {
      max-width: 600px;
      margin: auto;
      padding: 2rem;
    }
    .form-login label {
      display: block;
      margin-bottom: 1rem;
      font-weight: 600;
    }
    .form-login input {
      width: 100%;
      padding: 0.85rem 1rem;
      font-size: 1rem;
      margin-top: 0.25rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>


  <section class="section">
    <div class="container form-login">
      <h2 class="section-title" style="text-align:center;">Logowanie do konta</h2>
      <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>
      <form method="post">
        <label>Email:
          <input type="email" name="email" required>
        </label>
        <label>Hasło:
          <input type="password" name="password" required>
        </label>
        <div style="text-align:center; margin-top: 2rem;">
          <button type="submit" class="btn">Zaloguj się</button>
        </div>
      </form>
      <p style="text-align:center; margin-top:1rem;">Nie masz konta? <a href="register.php" style="color:#0a9396;">Zarejestruj się</a></p>
    </div>
  </section>

  <?php include '../includes/footer.php'; ?>

  <script src="/poradnia/script-fixed.js"></script>
  <button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
