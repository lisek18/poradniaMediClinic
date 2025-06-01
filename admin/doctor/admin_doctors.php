
<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
  header("Location: admin_login.php");
  exit;
}

$sort_column = $_GET['sort'] ?? 'id';
$sort_order = $_GET['order'] ?? 'desc';
$next_order = $sort_order === 'asc' ? 'desc' : 'asc';

$allowed_columns = ['id', 'name', 'specialization', 'department'];
if (!in_array($sort_column, $allowed_columns)) $sort_column = 'id';
if (!in_array($sort_order, ['asc', 'desc'])) $sort_order = 'desc';

$where = [];
$params = [];
$types = '';

if (!empty($_GET['id'])) {
  $where[] = 'id = ?';
  $params[] = (int)$_GET['id'];
  $types .= 'i';
}
if (!empty($_GET['name'])) {
  $where[] = 'name LIKE ?';
  $params[] = '%' . $_GET['name'] . '%';
  $types .= 's';
}
if (!empty($_GET['specialization'])) {
  $where[] = 'specialization LIKE ?';
  $params[] = '%' . $_GET['specialization'] . '%';
  $types .= 's';
}

$sql = "SELECT * FROM doctors";
if ($where) {
  $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY $sort_column $sort_order";

$stmt = $mysqli->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$doctors = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Zarządzanie lekarzami – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <style>
    .filter-form {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      align-items: center;
      margin-bottom: 2rem;
    }
    .filter-form input {
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }
    .filter-form .btn {
      white-space: nowrap;
    }
    th a {
      color: white;
      text-decoration: underline;
    }
  </style>
   <link rel="icon" type="image/png" href="image/favicon.png" />
</head>
<body>
<?php include '../includes/admin_header.php'; ?>

<div class="container" style="max-width:1000px; margin:auto; padding:3rem 1rem;">
  <h2 style="text-align:center; color:#0a9396;">Zarządzanie lekarzami</h2>

  <form method="get" class="filter-form">
    <input type="text" name="id" placeholder="ID" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
    <input type="text" name="name" placeholder="Imię i nazwisko" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
    <input type="text" name="specialization" placeholder="Specjalizacja" value="<?= htmlspecialchars($_GET['specialization'] ?? '') ?>">
    <button type="submit" class="btn">Filtruj</button>
    <a href="admin_doctors.php" class="btn" style="background:#ccc; color:black;">Wyczyść</a>
  </form>

  <table style="width:100%; border-collapse:collapse;">
    <thead>
      <tr style="background:#0a9396; color:white;">
        <th style="padding:10px;"><a href="?sort=id&order=<?= $next_order ?>">ID</a></th>
        <th><a href="?sort=name&order=<?= $next_order ?>">Imię i nazwisko</a></th>
        <th><a href="?sort=specialization&order=<?= $next_order ?>">Specjalizacja</a></th>
        <th><a href="?sort=department&order=<?= $next_order ?>">Dział</a></th>
        <th>Akcja</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($doc = $doctors->fetch_assoc()): ?>
      <tr style="border-bottom:1px solid #ddd;">
        <td style="padding:10px;"><?= $doc['id'] ?></td>
        <td><?= htmlspecialchars($doc['name']) ?></td>
        <td><?= htmlspecialchars($doc['specialization']) ?></td>
        <td><?= htmlspecialchars($doc['department']) ?></td>
        <td><a href="?delete=<?= $doc['id'] ?>" onclick="return confirm('Na pewno usunąć?')">Usuń</a></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <br><br>
  <a href="/poradnia/admin/admin_dashboard.php" class="btn">← Wróć do panelu</a>
</div>

</body>
</html>
