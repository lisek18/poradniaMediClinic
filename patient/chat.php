<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$patient_id = $_SESSION['user_id'];
$doctor_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : null;

// Obsługa wysyłania wiadomości
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $doctor_id) {
  $msg = trim($_POST['message']);
  if (!empty($msg)) {
    $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, sender_role, message) VALUES (?, ?, 'patient', ?)");
    $stmt->bind_param("iis", $patient_id, $doctor_id, $msg);
    $stmt->execute();
    header("Location: chat.php?doctor_id=$doctor_id");
    exit;
  }
}

// Pobierz wszystkich lekarzy
$doctors = $mysqli->query("SELECT id, name FROM doctors");

// Historia wiadomości (jeśli wybrano lekarza)
$messages = [];
if ($doctor_id) {
  $stmt = $mysqli->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?)
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY sent_at ASC
  ");
  $stmt->bind_param("iiii", $patient_id, $doctor_id, $doctor_id, $patient_id);
  $stmt->execute();
  $messages = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Czat z lekarzem – Pacjent</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .chat-container {
      max-width: 1000px;
      margin: 4rem auto;
      display: flex;
      gap: 2rem;
    }
    .chat-sidebar {
      width: 250px;
      border-right: 1px solid #ccc;
      padding-right: 1rem;
    }
    .chat-messages {
      flex: 1;
    }
    .message {
      margin-bottom: 1rem;
    }
    .message.doctor { text-align: left; color: #0a9396; }
    .message.patient { text-align: right; color: #005f73; }

 

  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="chat-container">
  <div class="chat-sidebar">
    <h3>Lekarze</h3>
    <ul style="list-style: none;">
      <?php while ($doc = $doctors->fetch_assoc()): ?>
        <li><a href="?doctor_id=<?= $doc['id'] ?>"><?= htmlspecialchars($doc['name']) ?></a></li>
      <?php endwhile; ?>
    </ul>
  </div>

  <div class="chat-messages">
    <h3>Rozmowa z lekarzem <?= $doctor_id ? "#$doctor_id" : '' ?></h3>

    <?php if ($doctor_id): ?>
      <div style="max-height:400px; overflow-y:auto; margin-bottom:2rem; padding:1rem; border:1px solid #ddd;">
        <?php foreach ($messages as $msg): ?>
          <div class="message <?= $msg['sender_role'] ?>">
            <p><strong><?= ucfirst($msg['sender_role']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?></p>
            <small><?= $msg['sent_at'] ?></small>
          </div>
        <?php endforeach; ?>
      </div>

      <form method="POST">
        <textarea name="message" rows="3" style="width:100%;" placeholder="Napisz wiadomość..."></textarea><br>
        <button type="submit">Wyślij</button>
      </form>
    <?php else: ?>
      <p>Wybierz lekarza z listy po lewej stronie, aby rozpocząć rozmowę.</p>
    <?php endif; ?>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
<script src="/poradnia/script-fixed.js"></script>
</body>
</html>
