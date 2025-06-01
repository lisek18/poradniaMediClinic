<?php ob_start(); ?>

<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

$visit_id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['visit_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $card_number = trim($_POST['card_number']);
  $expiry = trim($_POST['expiry']);
  $cvc = trim($_POST['cvc']);

  // Prosta walidacja
  if (!$name || !$email || !$card_number || !$expiry || !$cvc) {
    $error = "Uzupełnij wszystkie pola.";
  } elseif (!preg_match('/^[0-9]{16}$/', $card_number)) {
    $error = "Nieprawidłowy numer karty.";
  } elseif (!preg_match('/^[0-9]{2}\/[0-9]{2}$/', $expiry)) {
    $error = "Data ważności musi mieć format MM/YY.";
  } elseif (!preg_match('/^[0-9]{3}$/', $cvc)) {
    $error = "Nieprawidłowy CVC.";
  } else {
    // Symulacja przetwarzania płatności
    sleep(2);

    // Zapisz, że opłacono
    $mysqli->query("UPDATE visits SET is_paid = 1 WHERE id = $visit_id");


header("Location: /poradnia/patient/my_visits.php?paid=success");



    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Symulacja płatności</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container" style="max-width:600px; margin-top:4rem;">
  <h2>Symulacja płatności online</h2>

  <?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
  <?php endif; ?>

  <form method="POST" style="margin-top:2rem;">
    <input type="hidden" name="visit_id" value="<?= htmlspecialchars($visit_id) ?>">

    <label>Imię i nazwisko:<br>
      <input type="text" name="name" style="width:100%;">
    </label><br><br>

    <label>Adres e-mail:<br>
      <input type="email" name="email" style="width:100%;">
    </label><br><br>

    <label>Numer karty:<br>
      <input type="text" name="card_number" maxlength="16" placeholder="1234567812345678" style="width:100%;">
    </label><br><br>

    <label>Data ważności (MM/YY):<br>
      <input type="text" name="expiry" maxlength="5" placeholder="12/28" style="width:100%;">
    </label><br><br>

    <label>CVC:<br>
      <input type="text" name="cvc" maxlength="3" placeholder="123" style="width:100%;">
    </label><br><br>

    <button type="submit" class="btn">Opłać teraz</button>
  </form>
</div>
</body>
</html>
<?php ob_end_flush(); ?>
