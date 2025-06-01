<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

// Usuwanie aktualności
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $mysqli->query("DELETE FROM news WHERE id = $id");
  header("Location: manage_news.php?deleted=1");
  exit;
}

$result = $mysqli->query("SELECT * FROM news ORDER BY published_at DESC");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Zarządzaj aktualnościami – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <style>
    .admin-container {
      max-width: 1000px;
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

    .success {
      color: green;
      text-align: center;
      margin-bottom: 1rem;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 2rem;
    }

    th, td {
      padding: 1rem;
      border-bottom: 1px solid #e2e8f0;
      text-align: left;
    }

    th {
      background-color: #e0f7fa;
      color: #005f73;
      font-size: 0.9rem;
      text-transform: uppercase;
    }

    .actions a {
      text-decoration: none;
      font-weight: bold;
      padding: 0.4rem 0.8rem;
      border-radius: 5px;
      transition: background 0.3s;
    }

    .actions a.delete {
      background-color: #ae2012;
      color: white;
    }

    .actions a.delete:hover {
      background-color: #9b1c10;
    }
  </style>
</head>
<body>

<?php include '../includes/admin_header.php'; ?>

<div class="admin-container">
  <h2>Zarządzaj aktualnościami</h2>

  <?php if (isset($_GET['deleted'])): ?>
    <p class="success">Aktualność została usunięta.</p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Tytuł</th>
        <th>Data publikacji</th>
        <th>Akcje</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['published_at']) ?></td>
          <td class="actions">
            <a class="delete" href="manage_news.php?delete=<?= $row['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę aktualność?')">Usuń</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>
