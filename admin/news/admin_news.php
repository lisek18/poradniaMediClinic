<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

// załaduj funkcję wysyłki maili
function notify_subscribers($mysqli, $title, $content, $date) {
  $result = $mysqli->query("SELECT email FROM newsletter_subscribers");

  $subject = "Nowa aktualność – $title";
  $body = "Data publikacji: $date\n\n"
        . "Tytuł: $title\n\n"
        . "Treść:\n$content\n\n"
        . "Zespół MediClinic";

  $headers = "From: kontakt@mediclinic.pl\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8";

  while ($row = $result->fetch_assoc()) {
    mail($row['email'], $subject, $body, $headers);
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title']);
  $content = trim($_POST['content']);
  $date = $_POST['date'];

  $stmt = $mysqli->prepare("INSERT INTO news (title, content, published_at) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $title, $content, $date);
  $stmt->execute();

  // wyślij maila do subskrybentów
  notify_subscribers($mysqli, $title, $content, $date);

  $success = "Dodano aktualność i wysłano powiadomienia do subskrybentów.";
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Dodaj aktualność – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <style>
    .admin-container {
      max-width: 900px;
      margin: 4rem auto;
      background: white;
      padding: 3rem;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    h2 {
      color: #0a9396;
      text-align: center;
      margin-bottom: 2rem;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    label {
      font-weight: 600;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    input, textarea {
      padding: 0.8rem;
      font-size: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
      width: 100%;
    }

    button {
      background-color: #0a9396;
      color: white;
      padding: 0.75rem 2rem;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
    }

    button:hover {
      background-color: #007f7f;
    }

    .success {
      color: green;
      text-align: center;
      margin-top: 1rem;
    }
  </style>
</head>
<body>

<?php include '../includes/admin_header.php'; ?>

<div class="admin-container">
  <h2>Dodaj nową aktualność</h2>

  <?php if (!empty($success)): ?>
    <p class="success"><?= $success ?></p>
  <?php endif; ?>

  <form method="POST">
    <label>
      Tytuł aktualności:
      <input type="text" name="title" required>
    </label>

    <label>
      Treść:
      <textarea name="content" rows="5" required></textarea>
    </label>

    <label>
      Data publikacji:
      <input type="date" name="date" required>
    </label>

    <button type="submit">Dodaj aktualność</button>
  </form>
</div>

</body>
</html>
