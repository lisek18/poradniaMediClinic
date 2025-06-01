<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: admin_login.php");
  exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=raport_statystyki.csv');

$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$output = fopen('php://output', 'w');

// Nagłówki
fputcsv($output, ['Metryka', 'Wartość']);

// Statystyki ogólne
$patients = $mysqli->query("SELECT COUNT(*) FROM patients")->fetch_row()[0];
$doctors = $mysqli->query("SELECT COUNT(*) FROM doctors")->fetch_row()[0];
$departments = $mysqli->query("SELECT COUNT(*) FROM departments")->fetch_row()[0];
$visits_total = $mysqli->query("SELECT COUNT(*) FROM visits")->fetch_row()[0];
$visits_this_month = $mysqli->query("SELECT COUNT(*) FROM visits WHERE MONTH(visit_datetime)=MONTH(CURDATE()) AND YEAR(visit_datetime)=YEAR(CURDATE())")->fetch_row()[0];
$avg_visits_per_day = $mysqli->query("SELECT ROUND(COUNT(*)/COUNT(DISTINCT DATE(visit_datetime)),1) FROM visits")->fetch_row()[0];
$survey_count = $mysqli->query("SELECT COUNT(*) FROM patient_satisfaction_surveys")->fetch_row()[0];
$avg_rating = $mysqli->query("SELECT ROUND(AVG(rating),2) FROM patient_satisfaction_surveys")->fetch_row()[0];

// Dane
fputcsv($output, ['Liczba pacjentów', $patients]);
fputcsv($output, ['Liczba lekarzy', $doctors]);
fputcsv($output, ['Liczba specjalizacji', $departments]);
fputcsv($output, ['Wszystkie wizyty', $visits_total]);
fputcsv($output, ['Wizyty w bieżącym miesiącu', $visits_this_month]);
fputcsv($output, ['Średnia wizyt dziennie', $avg_visits_per_day]);
fputcsv($output, ['Wypełnione ankiety', $survey_count]);
fputcsv($output, ['Średnia ocena z ankiet', $avg_rating]);

fclose($output);
exit;
