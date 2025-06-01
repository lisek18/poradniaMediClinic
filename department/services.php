<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Usługi – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
</head>
<body>
<?php include '../includes/header.php'; ?>


  <section class="section services">
    <div class="container">
      <h2 class="section-title">Nasze specjalizacje</h2>
      <div class="cards">
        <?php
          $result = $mysqli->query("SELECT * FROM departments");
          while ($row = $result->fetch_assoc()):
        ?>
        <div class="card">
          <img src="/poradnia/image/<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <p><?= htmlspecialchars($row['description']) ?></p>
          <a href="<?= htmlspecialchars($row['link']) ?>" class="btn">Zobacz więcej</a>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
  </section>

  <?php include '../includes/footer.php'; ?>



  <script src="/poradnia/script-fixed.js"></script>
  <button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
