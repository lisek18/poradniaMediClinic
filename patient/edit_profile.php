
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT first_name, middle_name, last_name, birth_date, email, phone, street, house_number, city, postal_code FROM patients WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $phone = trim($_POST['phone']);
  $street = trim($_POST['street']);
  $house_number = trim($_POST['house_number']);
  $city = trim($_POST['city']);
  $postal_code = trim($_POST['postal_code']);

  if (!$phone || !$street || !$house_number || !$city || !$postal_code) {
    $error = "Wszystkie pola muszą być uzupełnione.";
  } else {
    $update = $mysqli->prepare("UPDATE patients SET phone=?, street=?, house_number=?, city=?, postal_code=? WHERE id=?");
    $update->bind_param("sssssi", $phone, $street, $house_number, $city, $postal_code, $id);
    if ($update->execute()) {
      $success = "Dane zostały zaktualizowane.";
      $data = array_merge($data, compact('phone', 'street', 'house_number', 'city', 'postal_code'));
    } else {
      $error = "Błąd przy aktualizacji danych.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Edytuj dane – MediClinic</title>
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
      <p><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></p>
    </div>
    <div class="profile-wrapper">
      <div class="profile-sidebar">
        <h3>Panel pacjenta</h3>
        <?php include 'sidebar.php'; ?>
      </div>

      <div class="profile-main">
        <h3>Edytuj dane kontaktowe</h3><br>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <form method="post" class="form-grid" style="max-width:800px;">
          <label>Telefon:<input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" required></label>
          <label>Ulica:<input type="text" name="street" value="<?= htmlspecialchars($data['street']) ?>" required></label>
          <label>Nr domu/mieszkania:<input type="text" name="house_number" value="<?= htmlspecialchars($data['house_number']) ?>" required></label>
          <label>Miasto:<input type="text" name="city" value="<?= htmlspecialchars($data['city']) ?>" required></label>
          <label>Kod pocztowy:<input type="text" name="postal_code" value="<?= htmlspecialchars($data['postal_code']) ?>" required></label>
          <div style="grid-column: 1 / -1; text-align:center;">
            <button type="submit" class="btn">Zapisz zmiany</button>
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
