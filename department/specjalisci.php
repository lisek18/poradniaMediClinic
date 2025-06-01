<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
$departments = ["Ortopedia", "Kardiologia", "Dermatologia", "Neurologia", "Pediatria", "Okulistyka"];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nasi Specjaliści – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
  <style>
    .accordion-body.active { display: block; }
    .accordion-body { display: none; }
    .accordion-header.active { background-color: #cdeae4; }
  </style>
</head>
<body>
<?php include '../includes/header.php'; ?>


<section class="section container">
  <h2 class="section-title">Nasi lekarze</h2>
  <div class="accordion">
    <?php foreach ($departments as $dept): ?>
      <div class="accordion-item">
        <div class="accordion-header"><?= htmlspecialchars($dept) ?></div>
        <div class="accordion-body">
          <?php
            $stmt = $mysqli->prepare("SELECT * FROM doctors WHERE department = ?");
            $stmt->bind_param("s", $dept);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
          ?>
            <div class="specialist-card">
              <div class="specialist-photo"><img src="/poradnia/image/<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></div>
              <div class="specialist-info">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><strong>Specjalizacja:</strong> <?= htmlspecialchars($row['specialization']) ?></p>
                <p><strong>Biografia:</strong> <?= nl2br(htmlspecialchars($row['biography'])) ?></p>
                <p><strong>Kwalifikacje:</strong> <?= nl2br(htmlspecialchars($row['qualifications'])) ?></p>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>



<script>
document.querySelectorAll('.accordion-header').forEach(button => {
  button.addEventListener('click', () => {
    button.classList.toggle('active');
    button.nextElementSibling.classList.toggle('active');
  });
});
</script>
<?php include '../includes/footer.php'; ?>


<script src="/poradnia/script-fixed.js"></script>
<button id="scrollToTop" title="Wróć na górę">↑</button>
</body>
</html>
