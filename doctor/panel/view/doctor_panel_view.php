<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Panel lekarza – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css">
  <link rel="icon" href="/poradnia/image/favicon.png">
  <style>
    body {
      font-family: Inter, sans-serif;
      background-color: #f8fafc;
    }
    .doctor-container {
      max-width: 1100px;
      margin: 3rem auto;
      padding: 2rem;
      background: white;
      border-radius: 8px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
    }
    h2 {
      color: #0a9396;
      text-align: center;
      margin-bottom: 2rem;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      padding: 1rem;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
    }
    th {
      background-color: #e0f7fa;
      color: #005f73;
      text-transform: uppercase;
      font-size: 0.9rem;
    }
    tr:hover {
      background-color: #f1f5f9;
    }
    a.button-link {
      background-color: #0a9396;
      color: white;
      padding: 0.5rem 1rem;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    a.button-link:hover {
      background-color: #007f7f;
    }
  </style>
</head>
<body>

<?php include '../header.php'; ?>

<div class="doctor-container">
  <h2>Twoje wizyty</h2>

  <table>
    <tr>
      <th>Data wizyty</th>
      <th>Pacjent</th>
      <th>Akcja</th>
    </tr>
    <?php foreach ($visits as $visit): ?>
      <tr>
        <td><?= date("d.m.Y H:i", strtotime($visit['visit_datetime'])) ?></td>
        <td><?= htmlspecialchars($visit['first_name'] . ' ' . $visit['last_name']) ?></td>
        <td><a class="button-link" href="/poradnia/doctor/visit/edit_visit.php?id=<?= $visit['id'] ?>">Edytuj wizytę</a></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <a href="/poradnia/doctor/chat/chat.php" class="button-link" style="margin-top: 1rem; display: inline-block;">💬 Czat z pacjentami</a>
</div>

<?php include '../../includes/footer.php'; ?>
<script src="/poradnia/script-fixed.js"></script>
<button id="scrollToTop" title="Wróć na górę">↑</button>

</body>
</html>
