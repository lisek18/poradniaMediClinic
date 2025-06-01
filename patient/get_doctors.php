<?php
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (isset($_GET['department_id']) && isset($_GET['visit_date']) && isset($_GET['visit_time'])) {
    $department_id = (int) $_GET['department_id'];
    $visit_date = $_GET['visit_date'];     // format: YYYY-MM-DD
    $visit_time = $_GET['visit_time'];     // format: HH:MM
    $visit_datetime = $visit_date . ' ' . $visit_time . ':00';

    // Pobierz nazwę działu
    $depRes = $mysqli->query("SELECT name FROM departments WHERE id = $department_id");
    if ($depRes && $depRes->num_rows > 0) {
        $depRow = $depRes->fetch_assoc();
        $department_name = $depRow['name'];

        // Pobierz wszystkich lekarzy tej specjalizacji
        $doctors = $mysqli->query("SELECT id, name FROM doctors WHERE department = '$department_name'");

        $available = [];

        while ($doc = $doctors->fetch_assoc()) {
            $doc_id = $doc['id'];

            // Sprawdź czy lekarz nie ma już wizyty o tej porze
            $conflict = $mysqli->query("SELECT id FROM visits WHERE doctor_id = $doc_id AND visit_datetime = '$visit_datetime'");

            if ($conflict->num_rows == 0) {
                $available[] = $doc;
            }
        }

        if (count($available) > 0) {
            echo "<option value=''>-- Wybierz lekarza --</option>";
            foreach ($available as $doc) {
                echo "<option value='{$doc['id']}'>{$doc['name']}</option>";
            }
        } else {
            echo "<option value=''>Brak dostępnych lekarzy</option>";
        }
    } else {
        echo "<option value=''>Błąd odczytu działu</option>";
    }
}
?>
