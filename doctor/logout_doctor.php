<?php
session_start();

// Usunięcie sesji i ciasteczek
$_SESSION = [];
session_unset();
session_destroy();

// Usunięcie ciasteczka e-maila (jeśli było ustawione)
if (isset($_COOKIE['doctor_email'])) {
  setcookie("doctor_email", "", time() - 3600, "/");
}

// Przekierowanie do strony logowania
header("Location: /poradnia/home/index.php");
exit;
?>
