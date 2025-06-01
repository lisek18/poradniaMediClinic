<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$patient_id = $_SESSION['user_id'];
$result = $mysqli->query("SELECT first_name, last_name FROM patients WHERE id = $patient_id");
$data = $result ? $result->fetch_assoc() : ['first_name' => 'Pacjent', 'last_name' => ''];

// Pobierz wizyty pacjenta
$stmt = $mysqli->prepare("
SELECT v.id, v.visit_datetime, v.visit_description, v.recommendations, v.visit_notes, 
       d.name AS doctor, dep.name AS department
FROM visits v
JOIN doctors d ON v.doctor_id = d.id
JOIN departments dep ON v.department_id = dep.id
WHERE v.patient_id = ?
ORDER BY v.visit_datetime DESC
");

$stmt->bind_param("i", $patient_id);
$stmt->execute();
$visits = $stmt->get_result();



?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Moje wizyty – MediClinic</title>
   <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="max-width:1100px; margin-top: 6rem; margin-bottom: 6rem;">
    <div class="profile-header">
      <h2>Twój profil</h2>
      <p><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></p>
    </div>

    <div class="profile-wrapper">
      <div class="profile-sidebar">
        <h3>Panel Pacjenta</h3>
        <?php include 'sidebar.php'; ?>
      </div>

      <div class="profile-main">
        <h3>Historia wizyt</h3>

       <?php while ($row = $visits->fetch_assoc()): 
    $visit_datetime = new DateTime($row['visit_datetime']);
    $today = new DateTime();
    $interval = $today->diff($visit_datetime);
    $days = (int)$interval->format('%r%a');
    $status = $days > 0 ? "Wizyta za $days dni" : ($days === 0 ? "Wizyta dziś" : "Wizyta odbyła się " . abs($days) . " dni temu");
    $survey_result = $mysqli->query("SELECT 1 FROM patient_satisfaction_surveys WHERE visit_id = {$row['id']} AND patient_id = $patient_id");
$survey_filled = $survey_result->num_rows > 0;


    // Pobierz usługę z odpowiedniej tabeli na podstawie specjalizacji
    $department = strtolower($row['department']);
    $department = str_replace(['ł','ś','ż','ź','ć','ę','ó','ń','ą'],
                              ['l','s','z','z','c','e','o','n','a'], $department);
    $service_table = $department . "_services";

    $service_name = $service_price = null;

    $query = "SELECT s.service_name, s.price 
              FROM `$service_table` s 
              JOIN visits v ON s.id = v.service_id 
              WHERE v.id = {$row['id']}";

    $service_result = $mysqli->query($query);
    if ($service_result && $service_result->num_rows > 0) {
        $service = $service_result->fetch_assoc();
        $service_name = $service['service_name'];
        $service_price = $service['price'];
        // Pobierz status płatności
$is_paid_result = $mysqli->query("SELECT is_paid FROM visits WHERE id = {$row['id']}");
$is_paid = $is_paid_result->fetch_assoc()['is_paid'] ?? 0;

    }
?>


        <div class="visit-box" style="background-color:#f9f9f9; border:1px solid #ddd; border-radius:8px; padding:1rem; margin-bottom:1rem;">

  <p><strong>Data:</strong> <?= $visit_datetime->format('Y-m-d H:i'); ?></p>
  <p><strong>Status:</strong> <?= $status; ?></p>
  <p><strong>Lekarz:</strong> <?= htmlspecialchars($row['doctor']); ?></p>
  <p><strong>Specjalizacja:</strong> <?= htmlspecialchars($row['department']); ?></p>
  <p><strong>Opis pacjenta:</strong> <?= nl2br(htmlspecialchars($row['visit_description'])); ?></p>
  <p><strong>Opis wizyty:</strong> <?= nl2br(htmlspecialchars($row['visit_notes'])); ?></p>

  <?php if (!empty($service_name)): ?>
    <p><strong>Wykonane badanie:</strong> <?= htmlspecialchars($service_name); ?></p>
  <?php endif; ?>

  <p><strong>Zalecenia:</strong> <?= nl2br(htmlspecialchars($row['recommendations'])); ?></p>

  <p><strong>Załączniki wgrane przez lekarza:</strong><br>
    <?php
    $docs = $mysqli->query("SELECT file_name, file_path FROM patient_documents WHERE patient_id = $patient_id AND visit_id = {$row['id']}");
    if ($docs->num_rows > 0):
      while ($doc = $docs->fetch_assoc()):
    ?>
      <a href="<?= $doc['file_path'] ?>" target="_blank"><?= $doc['file_name'] ?></a><br>
    <?php endwhile;
    else: ?>
      Brak załączników.
    <?php endif; ?>
  </p>

  <?php if (!empty($service_name)): ?>
    <p><strong>Status płatności:</strong>
      <?php if (!$is_paid): ?>
        <span style="color:red;">Nieopłacona</span><br>
        <form method="GET" action="payment_sim.php" style="display:inline;">
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <button type="submit" class="btn" style="margin-top:0.5rem;">Opłać badanie</button>
        </form>
      <?php else: ?>
        <span style="color:green;">Opłacona</span>
      <?php endif; ?>
    </p>
  <?php endif; ?>


  <p><strong>Ankieta:</strong>
    <?php if ($days < 0 && !$survey_filled): ?>
      <form method="GET" action="ankieta.php">
        <input type="hidden" name="visit_id" value="<?= $row['id'] ?>">
        <button type="submit">Wypełnij ankietę po wizycie</button>
      </form>
    <?php elseif ($days < 0 && $survey_filled): ?>
      <span style="color:green;">Ankieta została już wypełniona</span>
    <?php else: ?>
      <span style="color:gray;">Ankieta dostępna po odbyciu wizyty</span>
    <?php endif; ?>
  </p>

</div>
<?php endwhile; ?>

      </div> <!-- .profile-main -->
    </div> <!-- .profile-wrapper -->
</div> <!-- .container -->




<?php include '../includes/footer.php'; ?>
<script src="/poradnia/script-fixed.js"></script>
</body>
</html>
