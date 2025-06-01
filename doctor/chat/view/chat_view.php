<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Czat z pacjentem – Lekarz</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" href="/poradnia/image/favicon.png" />
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
    .message { margin-bottom: 1rem; }
    .message.doctor { text-align: right; }
    .message.patient { text-align: left; }
  </style>
</head>
<body>

<?php include '../header.php'; ?>

<div class="chat-container">
  <div class="chat-sidebar">
    <h3>Pacjenci</h3>
    <ul>
      <?php foreach ($patients as $p): ?>
        <li><a href="?patient_id=<?= $p['id'] ?>"><?= htmlspecialchars($p['first_name'] . ' ' . $p['last_name']) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="chat-messages">
    <h3>Rozmowa z pacjentem <?= $patientId ? "#$patientId" : '' ?></h3>

    <?php if ($patientId): ?>
      <div style="max-height:400px; overflow-y:auto; margin-bottom:2rem; padding:1rem; border:1px solid #ddd;">
        <?php foreach ($messages as $msg): ?>
          <div class="message <?= $msg['sender_role'] ?>">
            <p><strong><?= ucfirst($msg['sender_role']) ?>:</strong> <?= htmlspecialchars($msg['message']) ?></p>
            <small><?= $msg['sent_at'] ?></small>
          </div>
        <?php endforeach; ?>
      </div>

      <form method="POST">
        <textarea name="message" rows="3" style="width:100%;" placeholder="Odpowiedz pacjentowi..."></textarea><br>
        <button type="submit">Wyślij</button>
      </form>
    <?php else: ?>
      <p>Wybierz pacjenta z listy po lewej stronie, aby rozpocząć rozmowę.</p>
    <?php endif; ?>

    <a href="/poradnia/doctor/panel/panel.php" class="btn" style="margin-top:10px;">← Wróć do panelu</a>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
<script src="/poradnia/script-fixed.js"></script>
<button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
