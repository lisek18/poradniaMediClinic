
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin_login.php");
  exit;
}

if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $mysqli->query("DELETE FROM patients WHERE id = $id");
  header("Location: admin_manage_patients.php");
  exit;
}

$where = [];
$params = [];
$types = '';
if (!empty($_GET['id'])) {
  $where[] = 'id = ?';
  $params[] = (int)$_GET['id'];
  $types .= 'i';
}
if (!empty($_GET['name'])) {
  $where[] = '(first_name LIKE ? OR last_name LIKE ?)';
  $params[] = $params[] = '%' . $_GET['name'] . '%';
  $types .= 'ss';
}
if (!empty($_GET['email'])) {
  $where[] = 'email LIKE ?';
  $params[] = '%' . $_GET['email'] . '%';
  $types .= 's';
}
$sql = "SELECT * FROM patients";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY id DESC";
$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$patients = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Zarządzanie pacjentami – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    .container { max-width: 1100px; margin: auto; padding: 3rem 1rem; }
    table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
    th, td { border-bottom: 1px solid #ccc; padding: 0.75rem; }
    th { background-color: #e0f7fa; }
    .form-grid { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
    .form-grid input { padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px; }
    .btn-center {
      text-align: center;
      grid-column: 1 / -1;
      margin-bottom:50px;
    }
 </style>
  <link rel="icon" type="image/png" href="image/favicon.png" />
</head>
<body>
<?php include '../includes/admin_header.php'; ?>


<div class="container">
  <h2 style="text-align:center; color:#0a9396;">Zarządzanie pacjentami</h2>

  <form method="get" class="form-grid">
    <input type="text" name="id" placeholder="ID" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
    <input type="text" name="name" placeholder="Imię lub nazwisko" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
    <input type="text" name="email" placeholder="Email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>">
    <button class="btn">Filtruj</button>
    <a href="admin_manage_patients.php" class="btn" style="background:#ccc; color:black;">Wyczyść</a>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Imię</th><th>Nazwisko</th><th>Email</th><th>Miasto</th><th>Telefon</th><th>Akcja</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $patients->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['first_name']) ?></td>
        <td><?= htmlspecialchars($row['last_name']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= htmlspecialchars($row['city']) ?></td>
        <td><?= htmlspecialchars($row['phone']) ?></td>
        <td><a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Usunąć pacjenta?')">Usuń</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<div class="btn-center" style="margin-top: 2rem;">
<a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
    </div>

</body>
</html>
