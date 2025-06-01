<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$id = $_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT first_name, middle_name, last_name, birth_date, email, phone, street, house_number, city, postal_code, last_seen_news_id FROM patients WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

// sprawdzanie nowej aktualności
$unseen_news = null;
$news_stmt = $mysqli->prepare("SELECT id, title FROM news WHERE id > ? ORDER BY published_at DESC LIMIT 1");
$news_stmt->bind_param("i", $data['last_seen_news_id']);
$news_stmt->execute();
$unseen_news = $news_stmt->get_result()->fetch_assoc();




// Wyszukaj najbliższą wizytę pacjenta (w ciągu 2 dni od dziś)
$reminder = null;

$rem_stmt = $mysqli->prepare("
  SELECT v.visit_datetime, d.name AS doctor_name, dep.name AS department_name
  FROM visits v
  JOIN doctors d ON v.doctor_id = d.id
  JOIN departments dep ON v.department_id = dep.id
  WHERE v.patient_id = ? 
    AND DATE(v.visit_datetime) >= CURDATE()
    AND DATE(v.visit_datetime) <= DATE_ADD(CURDATE(), INTERVAL 2 DAY)
  ORDER BY v.visit_datetime ASC
  LIMIT 1
");
$rem_stmt->bind_param("i", $id);
$rem_stmt->execute();
$reminder = $rem_stmt->get_result()->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Twój profil – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <link rel="stylesheet" href="../css/sidebar-style.css" />
  <style>
    .profile-wrapper {
      display: flex;
      gap: 2rem;
      margin-top: 2rem;
      align-items: flex-start;
    }
    .profile-sidebar {
      width: 250px;
      background-color: #f1f5f9;
      padding: 1.5rem;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    .profile-sidebar h3 {
      margin-bottom: 1rem;
      font-size: 1.1rem;
      color: #005f73;
    }
    .profile-sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .profile-sidebar li {
      margin-bottom: 1rem;
    }
    .profile-sidebar a {
      text-decoration: none;
      color: #0a9396;
      font-weight: 600;
    }
    .profile-main {
      flex: 1;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem 2rem;
    }
    .form-grid label {
      display: flex;
      flex-direction: column;
      font-weight: 600;
    }
    .form-grid input {
      padding: 0.85rem 1rem;
      font-size: 1rem;
      border-radius: 6px;
      border: 1px solid #ccc;
    }
    .form-grid input:disabled {
      background-color: #f0f0f0;
    }
    @media (max-width: 768px) {
      .profile-wrapper {
        flex-direction: column;
      }
      .form-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>
<?php if ($unseen_news): ?>
  <div style="background: #0a9396; color: white; padding: 1rem; text-align: center; margin: 2rem auto 0; max-width: 1100px; border-radius: 6px;">
    📰 <strong>Nowa aktualność:</strong> <?= htmlspecialchars($unseen_news['title']) ?>
    <a href="/poradnia/news.php?seen=1" style="color: #ffe; margin-left: 1rem; text-decoration: underline;">Zobacz</a>
  </div>
<?php endif; ?>
<?php if ($reminder): ?>
  <div style="background: #ca6702; color: white; padding: 1rem; text-align: center; margin: 1rem auto 0; max-width: 1100px; border-radius: 6px;">
    ⏰ <strong>Przypomnienie:</strong> Masz wizytę u dr. <?= htmlspecialchars($reminder['doctor_name']) ?> (<?= htmlspecialchars($reminder['department_name']) ?>)
    dnia <strong><?= date('d.m.Y H:i', strtotime($reminder['visit_datetime'])) ?></strong>.
  </div>
<?php endif; ?>




<section class="section">
  <div class="container" style="max-width:1100px;">
    <div class="profile-header">
  <h2>Twój profil</h2>
  <p><?= htmlspecialchars($data['first_name'] . ' ' . $data['last_name']) ?></p>
</div>

    <div class="profile-wrapper">
      <div class="profile-sidebar">
        <h3>Panel pacjenta</h3>
        <?php include 'sidebar.php'; ?>
      </div>

      <div class="profile-main">
        <div class="form-section">
          <h3>Dane osobowe</h3><br>
          <div class="form-grid">
            <label>Imię:<input type="text" value="<?= htmlspecialchars($data['first_name']) ?>" disabled></label>
            <label>Drugie imię:<input type="text" value="<?= htmlspecialchars($data['middle_name']) ?>" disabled></label>
            <label>Nazwisko:<input type="text" value="<?= htmlspecialchars($data['last_name']) ?>" disabled></label>
            <label>Data urodzenia:<input type="date" value="<?= htmlspecialchars($data['birth_date']) ?>" disabled></label>
          </div>
        </div>
<br><br>
        <div class="form-section">
          <h3>Dane kontaktowe</h3><br>
          <div class="form-grid">
            <label>Email:<input type="email" value="<?= htmlspecialchars($data['email']) ?>" disabled></label>
            <label>Telefon:<input type="text" value="<?= htmlspecialchars($data['phone']) ?>" disabled></label>
            <label>Ulica:<input type="text" value="<?= htmlspecialchars($data['street']) ?>" disabled></label>
            <label>Nr domu/mieszkania:<input type="text" value="<?= htmlspecialchars($data['house_number']) ?>" disabled></label>
            <label>Miasto:<input type="text" value="<?= htmlspecialchars($data['city']) ?>" disabled></label>
            <label>Kod pocztowy:<input type="text" value="<?= htmlspecialchars($data['postal_code']) ?>" disabled></label>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="/poradnia/script-fixed.js"></script>
<button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
