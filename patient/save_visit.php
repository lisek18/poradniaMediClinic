<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $patient_id = $_POST['patient_id'];
  $doctor_id = $_POST['doctor_id'];
  $department_id = $_POST['department_id'];
  $visit_date = $_POST['visit_date'];     // np. 2025-05-26
  $visit_time = $_POST['visit_time'];     // np. 14:00
  $description = $_POST['visit_description'];

  // Zabezpiecz przed brakiem pola
  if (!$visit_date || !$visit_time) {
    die("Brakuje daty lub godziny wizyty.");
  }

  $visit_datetime = $visit_date . ' ' . $visit_time . ':00';

  $stmt = $mysqli->prepare("INSERT INTO visits (patient_id, doctor_id, department_id, visit_datetime, visit_description) VALUES (?, ?, ?, ?, ?)");

  if (!$stmt) {
    die("Błąd przygotowania zapytania: " . $mysqli->error);
  }

  $stmt->bind_param("iiiss", $patient_id, $doctor_id, $department_id, $visit_datetime, $description);

  if ($stmt->execute()) {
    header("Location: my_visits.php?success=1");
exit;

  } else {
    die("Błąd zapisu wizyty: " . $stmt->error);
  }
}
?>
