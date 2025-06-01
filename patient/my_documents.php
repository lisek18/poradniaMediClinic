<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$patient_id = $_SESSION['user_id'];

// Pobierz imię i nazwisko pacjenta
$result = $mysqli->query("SELECT first_name, last_name FROM patients WHERE id = $patient_id");
$data = $result ? $result->fetch_assoc() : ['first_name' => 'Pacjent', 'last_name' => ''];

// Obsługa usuwania dokumentu
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $doc_id = (int) $_GET['delete'];
    $res = $mysqli->query("SELECT file_path FROM medical_documents WHERE id = $doc_id AND patient_id = $patient_id");
    if ($res && $row = $res->fetch_assoc()) {
        $full_path = $_SERVER['DOCUMENT_ROOT'] . $row['file_path'];
        if (file_exists($full_path)) {
            unlink($full_path);
        }
        $mysqli->query("DELETE FROM medical_documents WHERE id = $doc_id AND patient_id = $patient_id");
        header("Location: my_documents.php?deleted=1");
        exit;
    }
}

// Pobierz dokumenty
$documents = $mysqli->query("SELECT id, file_name, file_path, file_type, uploaded_at FROM medical_documents WHERE patient_id = $patient_id ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Moje dokumenty – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="container" style="max-width:1100px; margin-top: 6rem; margin-bottom: 6rem;">
  <div class="profile-header">
    <h2>Twój profil</h2>
    <p><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></p>
  </div>

  <div class="profile-wrapper">
    <div class="profile-sidebar">
      <h3>Panel Pacjenta</h3>
      <?php include 'sidebar.php'; ?>
    </div>

    <div class="profile-main">
      <h3>Twoje dokumenty medyczne</h3>

      <?php if (isset($_GET['deleted'])): ?>
        <p style="color: green;">Dokument został usunięty.</p>
      <?php endif; ?>

      <?php if ($documents->num_rows > 0): ?>
        <table class="documents-table" style="width:100%; border-collapse: collapse;">
          <thead style="background: #f3f3f3;">
            <tr>
              <th style="text-align:left; padding:10px;">Nazwa pliku</th>
              <th>Typ</th>
              <th>Rozmiar</th>
              <th>Data dodania</th>
              <th>Akcje</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($doc = $documents->fetch_assoc()):
              $file_path = $_SERVER['DOCUMENT_ROOT'] . $doc['file_path'];
              $file_size = file_exists($file_path) ? round(filesize($file_path)/1024) . ' KB' : 'brak';
            ?>
              <tr style="border-bottom:1px solid #ddd;">
                <td style="padding:10px;"><?= htmlspecialchars($doc['file_name']) ?></td>
                <td style="text-align:center;"><?= $doc['file_type'] ?></td>
                <td style="text-align:center;"><?= $file_size ?></td>
                <td style="text-align:center;"><?= $doc['uploaded_at'] ?></td>
                <td style="text-align:center;">
                  <a href="<?= $doc['file_path'] ?>" target="_blank">👁️</a>
                  <a href="<?= $doc['file_path'] ?>" download>📥</a>
                  <a href="my_documents.php?delete=<?= $doc['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten dokument?')">🗑️</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>Nie dodano jeszcze żadnych dokumentów.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
