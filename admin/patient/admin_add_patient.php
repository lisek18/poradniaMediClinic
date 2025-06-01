
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$errors = [];
$success = "";

if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first = $_POST['first_name'];
  $middle = $_POST['middle_name'] ?? null;
  $last = $_POST['last_name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $birth = $_POST['birth_date'];
  $street = $_POST['street'];
  $number = $_POST['house_number'];
  $city = $_POST['city'];
  $zip = $_POST['postal_code'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  if ($first && $last && $email && $phone && $birth && $street && $number && $city && $zip && $_POST['password']) {
    $stmt = $mysqli->prepare("INSERT INTO patients (first_name, middle_name, last_name, birth_date, email, phone, street, house_number, city, postal_code, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $first, $middle, $last, $birth, $email, $phone, $street, $number, $city, $zip, $password);
    if ($stmt->execute()) {
      $success = "Pacjent został dodany.";
    } else {
      $errors[] = "Błąd przy dodawaniu pacjenta.";
    }
  } else {
    $errors[] = "Wszystkie pola obowiązkowe muszą być uzupełnione.";
  }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dodaj pacjenta – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    .container { max-width: 800px; margin: auto; padding: 3rem 1rem; }
    form label { display: block; margin-top: 1rem; font-weight: bold; }
    form input { width: 100%; padding: 0.6rem; margin-top: 0.5rem; border: 1px solid #ccc; border-radius: 6px; }
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

<div class="container">
  <h2 style="text-align:center; color:#0a9396;">Dodaj pacjenta</h2>
  <?php if ($errors) echo "<p style='color:red;'>" . implode('<br>', $errors) . "</p>"; ?>
  <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>

  <form method="post">
    <label>Imię*<input name="first_name" required></label>
    <label>Drugie imię<input name="middle_name"></label>
    <label>Nazwisko*<input name="last_name" required></label>
    <label>Data urodzenia*<input type="date" name="birth_date" required></label>
    <label>Email*<input type="email" name="email" required></label>
    <label>Telefon*<input name="phone" required></label>
    <label>Ulica*<input name="street" required></label>
    <label>Nr domu/mieszkania*<input name="house_number" required></label>
    <label>Miasto*<input name="city" required></label>
    <label>Kod pocztowy*<input name="postal_code" required></label>
    <label>Hasło*<input type="password" name="password" required></label>
    <br>
    <button class="btn">Dodaj pacjenta</button>
  </form>
</div>
<div class="btn-center" style="margin-top: 2rem;">
<a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
    </div>

</body>
</html>
