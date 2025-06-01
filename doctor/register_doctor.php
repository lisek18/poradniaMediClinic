<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $specialization = trim($_POST['specialization']);
  $department = trim($_POST['department']);
  $biography = trim($_POST['biography']);
  $qualifications = trim($_POST['qualifications']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];

  // Walidacja
  if ($password !== $confirm_password) {
    $errors[] = "Hasła nie są zgodne.";
  }

  $checkEmail = $mysqli->prepare("SELECT email FROM doctor_logins WHERE email = ?");
  $checkEmail->bind_param("s", $email);
  $checkEmail->execute();
  $checkEmail->store_result();
  if ($checkEmail->num_rows > 0) {
    $errors[] = "Ten adres e-mail jest już zarejestrowany.";
  }

  if (empty($errors)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO doctors (name, specialization, department, biography, qualifications) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $specialization, $department, $biography, $qualifications);

    if ($stmt->execute()) {
      $doctor_id = $stmt->insert_id;
      $login = $mysqli->prepare("INSERT INTO doctor_logins (doctor_id, email, password) VALUES (?, ?, ?)");
      $login->bind_param("iss", $doctor_id, $email, $hashed);
      $login->execute();

      $success = "Rejestracja zakończona sukcesem. Możesz się teraz zalogować.";
    } else {
      $errors[] = "Wystąpił błąd podczas rejestracji.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Rejestracja lekarza – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    body {
      font-family: Inter, sans-serif;
      background-color: #f7fafc;
    }
    .container {
      max-width: 600px;
      margin: auto;
      padding: 3rem 1rem;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    h2 {
      text-align: center;
      color: #0a9396;
      margin-bottom: 2rem;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    label {
      font-weight: bold;
    }
    input, textarea {
      padding: 0.6rem;
      font-size: 1rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .btn {
      background: #0a9396;
      color: white;
      border: none;
      padding: 0.8rem;
      font-weight: bold;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #007f7f;
    }
    ul {
      color: red;
      padding-left: 1.5rem;
    }
    .success {
      color: green;
      text-align: center;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Rejestracja lekarza</h2>

    <?php if (!empty($errors)): ?>
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
      <p class="success"><?= $success ?></p>
    <?php endif; ?>

    <form method="post">
      <label>Imię i nazwisko:
        <input type="text" name="name" required>
      </label>

      <label>Specjalizacja:
        <input type="text" name="specialization" required>
      </label>

      <label>Oddział:
        <input type="text" name="department" required>
      </label>

      <label>Biografia:
        <textarea name="biography" rows="3"></textarea>
      </label>

      <label>Kwalifikacje:
        <textarea name="qualifications" rows="3"></textarea>
      </label>

      <label>Email:
        <input type="email" name="email" required>
      </label>

      <label>Hasło:
        <input type="password" name="password" required>
      </label>

      <label>Powtórz hasło:
        <input type="password" name="confirm_password" required>
      </label>

      <button type="submit" class="btn">Zarejestruj się</button>
    </form>
  </div>
</body>
</html>
