
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name = trim($_POST['first_name']);
  $middle_name = trim($_POST['middle_name']);
  $last_name = trim($_POST['last_name']);
  $birth_date = $_POST['birth_date'];
  $email = trim($_POST['email']);
  $phone = trim($_POST['phone']);
  $street = trim($_POST['street']);
  $house_number = trim($_POST['house_number']);
  $city = trim($_POST['city']);
  $postal_code = trim($_POST['postal_code']);
  $password = $_POST['password'];

  $today = new DateTime();
$birth = new DateTime($birth_date);
$age = $today->diff($birth)->y;
if ($age < 18) {
  $errors[] = "Musisz mieć ukończone 18 lat, aby założyć konto.";
}
  $confirm_password = $_POST['confirm_password'];

  if (!$first_name || !$last_name || !$birth_date || !$email || !$phone || !$street || !$house_number || !$city || !$postal_code || !$password || !$confirm_password) {
    $errors[] = "Wszystkie pola obowiązkowe muszą być wypełnione.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Nieprawidłowy format adresu e-mail.";
  }
  if (!preg_match("/^[0-9]{9}$/", $phone)) {
    $errors[] = "Numer telefonu musi zawierać 9 cyfr.";
  }
  if ($password !== $confirm_password) {
    $errors[] = "Hasła nie są takie same.";
  }
  if (strlen($password) < 6) {
    $errors[] = "Hasło musi zawierać co najmniej 6 znaków.";
  }
  if (stripos($password, $first_name) !== false || stripos($password, $last_name) !== false) {
    $errors[] = "Hasło nie może zawierać Twojego imienia ani nazwiska.";
  }

  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("INSERT INTO patients (first_name, middle_name, last_name, birth_date, email, phone, street, house_number, city, postal_code, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $first_name, $middle_name, $last_name, $birth_date, $email, $phone, $street, $house_number, $city, $postal_code, $hashed_password);
    if ($stmt->execute()) {
      $_SESSION['user_id'] = $stmt->insert_id;
      setcookie("user_email", $email, time() + 3600, "/");
      header("Location: profil.php");
      exit;
    } else {
      $errors[] = "Wystąpił błąd podczas rejestracji. Spróbuj ponownie.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Rejestracja – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia//image/favicon.png" />

</head>
<body>
<?php include '../includes/header.php'; ?>


  <section class="section">
    <div class="container" style="max-width:700px; margin:auto;">
  
      <?php if (!empty($errors)) { echo "<ul style='color:red;'>"; foreach ($errors as $e) echo "<li>$e</li>"; echo "</ul>"; } ?>

      <form method="post" >
      <section class="section container register-section">
  <h2 class="section-title">Rejestracja pacjenta</h2>

  <div class="form-section">
    <h3>Dane osobowe</h3>
    <div class="form-grid">
      <label>Imię*<input type="text" name="first_name" required></label>
      <label>Drugie imię<input type="text" name="middle_name"></label>
      <label>Nazwisko*<input type="text" name="last_name" required></label>
      <label>Data urodzenia*<input type="date" name="birthdate" required></label>
    </div>
  </div>

  <div class="form-section">
    <h3>Dane kontaktowe</h3>
    <div class="form-grid">
      <label>Email*<input type="email" name="email" required></label>
      <label>Telefon (9 cyfr)*<input type="tel" name="phone" required></label>
      <label>Ulica*<input type="text" name="street" required></label>
      <label>Nr domu/mieszkania*<input type="text" name="house_number" required></label>
      <label>Miasto*<input type="text" name="city" required></label>
      <label>Kod pocztowy*<input type="text" name="postal_code" required></label>
      <label>Hasło*<input type="password" name="password" required></label>
      <label>Powtórz hasło*<input type="password" name="confirm_password" required></label>
    </div>
  </div>

  <div class="register-submit">
    <button type="submit" class="btn">Zarejestruj się</button>
  </div>
</section>

      </form>
    </div>
  </section>

  <?php include '../includes/footer.php'; ?>

  <script src="/poradnia/script-fixed.js"></script>
  <button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
