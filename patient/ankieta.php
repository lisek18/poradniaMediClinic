<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];
$visit_id = isset($_GET['visit_id']) ? (int)$_GET['visit_id'] : (int)$_POST['visit_id'];

$stmt = $mysqli->prepare("SELECT first_name, last_name FROM patients WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
// Sprawdzenie, czy ankieta już została wypełniona
$already_filled = $mysqli->query("SELECT 1 FROM patient_satisfaction_surveys WHERE patient_id = $id AND visit_id = $visit_id")->num_rows > 0;
if ($already_filled) {
  $error = "Już wypełniłeś ankietę dla tej wizyty.";
}

$stmt->close();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rating = (int)$_POST['rating'];
  $service_quality = trim($_POST['service_quality']);
  $suggestions = trim($_POST['suggestions']);

  if ($already_filled) {

  $error = "Ta ankieta została już wysłana wcześniej.";
} elseif ($rating < 1 || $rating > 5 || empty($service_quality)) {
  $error = "Wypełnij poprawnie wszystkie wymagane pola.";
} else {
  $insert = $mysqli->prepare("INSERT INTO patient_satisfaction_surveys (visit_id, patient_id, rating, service_quality, suggestions) VALUES (?, ?, ?, ?, ?)");
  $insert->bind_param("iiiss", $visit_id, $id, $rating, $service_quality, $suggestions);
  if ($insert->execute()) {
    $success = "Dziękujemy za przesłanie opinii!";
  } else {
    $error = "Wystąpił błąd przy zapisie ankiety.";
  }
}

}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Ankieta – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
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
        <h3>Twoja opinia jest dla nas ważna</h3>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <form method="post" style="max-width:600px; margin: 0 auto; display: flex; flex-direction: column; gap: 1.5rem;">

        <label style="display: flex; flex-direction: column; align-items: center; text-align: center;">
 <b> Ocena jakości leczenia (1 – najgorsza, 5 – najlepsza) </b>
  <select name="rating" required style="width: 100%; max-width: 300px; margin-top: 0.5rem;">
    <option value="">-- wybierz ocenę --</option>
    <option value="1">1 - Bardzo źle</option>
    <option value="2">2</option>
    <option value="3">3 - Średnio</option>
    <option value="4">4</option>
    <option value="5">5 - Bardzo dobrze</option>
  </select>
</label>

<label style="display: flex; flex-direction: column; align-items: center; text-align: center;">
 <b> Co najbardziej Ci się podobało w naszej opiece?</b>
  <textarea name="service_quality" rows="4" required style="width: 100%; max-width: 600px; margin-top: 0.5rem;"></textarea>
</label>

<label style="display: flex; flex-direction: column; align-items: center; text-align: center;">
 <b> Jakie zmiany chciał(a)byś zaproponować?</b>
  <textarea name="suggestions" rows="4" style="width: 100%; max-width: 600px; margin-top: 0.5rem;"></textarea>
</label>


          <div style="grid-column: 1 / -1; text-align:center;">
            <button type="submit" class="btn">Wyślij ankietę</button>
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
