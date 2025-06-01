<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$patient_id = $_SESSION['user_id'];

// Pobranie imienia i nazwiska pacjenta
$result = $mysqli->query("SELECT first_name, last_name FROM patients WHERE id = $patient_id");
$data = $result ? $result->fetch_assoc() : ['first_name' => 'Pacjent', 'last_name' => ''];

?>

<!DOCTYPE html>
<html lang="pl">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

  <meta charset="UTF-8" />
  <title>Umów wizytę – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
</head>


<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="max-width:1100px; margin-top: 6rem; margin-bottom: 6rem;"> <!-- ZWIĘKSZONA PRZERWA -->

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
        <h3>Umów nową wizytę</h3>

        <form action="save_visit.php" method="POST" class="form-grid">

        <label>
  Specjalizacja:
  <select name="department_id" id="department_id" required>
    <option value="">-- Wybierz --</option>
    <?php
    $res = $mysqli->query("SELECT id, name FROM departments");
    while ($row = $res->fetch_assoc()) {
      echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }
    ?>
  </select>
</label>

<label>
  Lekarz:
  <select name="doctor_id" id="doctor_id" required>
    <option value="">-- Wybierz specjalizację najpierw --</option>
  </select>
</label>


          <label>
            Data wizyty:
            <input type="text" id="visit_date" name="visit_date" required placeholder="Wybierz datę">


            
          </label>
          <label>
          Godzina wizyty:<br><br>
          <select name="visit_time" id="visit_time" required>
  <option value="">-- Wybierz godzinę --</option>
  <option value="08:00">08:00</option>
  <option value="09:00">09:00</option>
  <option value="10:00">10:00</option>
  <option value="11:00">11:00</option>
  <option value="12:00">12:00</option>
  <option value="13:00">13:00</option>
  <option value="14:00">14:00</option>
  <option value="15:00">15:00</option>
  <option value="16:00">16:00</option>
</select>
  </label>

          <label style="grid-column: 1 / -1;">
            Opis dolegliwości (opcjonalnie):
            <textarea name="visit_description" rows="4"></textarea>
          </label>

          <input type="hidden" name="patient_id" value="<?= $patient_id; ?>">

          <div class="register-submit" style="grid-column: 1 / -1;">
            <button type="submit">Zarezerwuj</button>
          </div>
        </form>
      </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department_id');
    const dateInput = document.getElementById('visit_date');
    const timeSelect = document.getElementById('visit_time');
    const doctorSelect = document.getElementById('doctor_id');

    function fetchDoctorsIfReady() {
        const depId = departmentSelect.value;
        const date = dateInput.value;
        const time = timeSelect.value;

        if (depId && date && time) {
            fetch(`get_doctors.php?department_id=${depId}&visit_date=${date}&visit_time=${time}`)
                .then(response => response.text())
                .then(data => {
                    doctorSelect.innerHTML = data;
                })
                .catch(err => {
                    doctorSelect.innerHTML = "<option value=''>Błąd ładowania</option>";
                });
        }
    }

    departmentSelect.addEventListener('change', fetchDoctorsIfReady);
    dateInput.addEventListener('change', fetchDoctorsIfReady);
    timeSelect.addEventListener('change', fetchDoctorsIfReady);
});
</script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
flatpickr("#visit_date", {
    minDate: "today",
    dateFormat: "Y-m-d",
    disableMobile: true,
});
</script>


<?php include '../includes/footer.php'; ?>
<script src="/poradnia/script-fixed.js"></script>
</body>
</html>
