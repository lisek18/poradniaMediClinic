
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

$success = "";
$errors = [];

// Usuwanie specjalizacji
if (isset($_GET['delete_department'])) {
  $id = (int)$_GET['delete_department'];
  $name = $mysqli->query("SELECT name FROM departments WHERE id=$id")->fetch_assoc()['name'];
  $mysqli->query("DELETE FROM departments WHERE id = $id");
  $mysqli->query("DROP TABLE IF EXISTS `" . strtolower($name) . "_services`");
  $success = "Usunięto specjalizację i jej badania.";
}

// Usuwanie badania
if (isset($_GET['delete_service']) && isset($_GET['table'])) {
  $id = (int)$_GET['delete_service'];
  $table = preg_replace('/[^a-z_]/', '', $_GET['table']);  // zabezpieczenie
  $mysqli->query("DELETE FROM `$table` WHERE id = $id");
  $success = "Usunięto badanie z tabeli $table.";
}

$departments = $mysqli->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Zarządzanie specjalizacjami i badaniami – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    .manage-box {
      max-width: 1100px;
      margin: auto;
      padding: 2rem;
    }
    h2, h3 {
      color: #0a9396;
    }
    table {
      width: 100%;
      margin-bottom: 2rem;
      border-collapse: collapse;
    }
    th, td {
      border-bottom: 1px solid #ccc;
      padding: 0.75rem;
    }
    th {
      background: #e0f7fa;
      text-align: left;
    }
    a.delete {
      color: red;
      font-weight: bold;
    }
  </style>
   <link rel="icon" type="image/png" href="image/favicon.png" />
</head>
<body>
<?php include '../includes/admin_header.php'; ?>
<div class="manage-box">
  <h2>Zarządzanie specjalizacjami</h2>
  <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
  <?php if ($errors) echo "<p style='color:red;'>".implode('<br>', $errors)."</p>"; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Nazwa</th><th>Opis</th><th>Obraz</th><th>Link</th><th>Akcja</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($dept = $departments->fetch_assoc()): ?>
      <tr>
        <td><?= $dept['id'] ?></td>
        <td><?= htmlspecialchars($dept['name']) ?></td>
        <td><?= htmlspecialchars($dept['description']) ?></td>
        <td><?= htmlspecialchars($dept['image_path']) ?></td>
        <td><?= htmlspecialchars($dept['link']) ?></td>
        <td><a href="?delete_department=<?= $dept['id'] ?>" class="delete" onclick="return confirm('Na pewno usunąć ten dział i wszystkie badania?')">Usuń</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <br><br>
  <h2>Zarządzanie badaniami</h2><br><br>
  <?php
    $result = $mysqli->query("SELECT name FROM departments");
    while ($row = $result->fetch_assoc()):
      $table = strtolower($row['name']) . "_services";
      $services = $mysqli->query("SELECT * FROM `$table`");
  ?>
    <h3><?= htmlspecialchars($row['name']) ?></h3>
    <table>
      <thead>
        <tr><th>ID</th><th>Usługa</th><th>Cena</th><th>Akcja</th></tr>
      </thead>
      <tbody>
        <?php while ($srv = $services->fetch_assoc()): ?>
        <tr>
          <td><?= $srv['id'] ?></td>
          <td><?= htmlspecialchars($srv['service_name']) ?></td>
          <td><?= htmlspecialchars($srv['price']) ?></td>
          <td><a class="delete" href="?delete_service=<?= $srv['id'] ?>&table=<?= $table ?>" onclick="return confirm('Na pewno usunąć usługę?')">Usuń</a></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endwhile; ?>

  <a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
</div>

</body>
</html>
