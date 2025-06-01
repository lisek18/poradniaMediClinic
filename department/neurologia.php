<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "mediclinic");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Neurologia – MediClinic</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
</head>
<body>
<?php include '../includes/header.php'; ?>

  <section class="hero-ortopedia" style="background-image: url('/poradnia/image/neurologia.jpg')">
    <div class="overlay"></div>
    <div class="container hero-content">
      <h1>Poradnia Neurologiczna</h1>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <h2>Cennik badań i zabiegów</h2>
      <table class="pricing-table">
        <thead>
          <tr>
            <th>Usługa</th>
            <th>Cena</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $res = $mysqli->query("SELECT * FROM neurologia_services");
            while ($row = $res->fetch_assoc()):
          ?>
          <tr>
            <td><?= htmlspecialchars($row['service_name']) ?></td>
            <td><?= htmlspecialchars($row['price']) ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

      <br><br>
      <h2>Nasi specjaliści</h2>
      <div class="doctors-grid">
        <?php
          $dept = "Neurologia";
          $stmt = $mysqli->prepare("SELECT * FROM doctors WHERE department = ?");
          $stmt->bind_param("s", $dept);
          $stmt->execute();
          $res = $stmt->get_result();
          while ($doc = $res->fetch_assoc()):
        ?>
        <div class="doctor-card">
          <div class="doctor-image">
            <img src="/poradnia/image/<?= htmlspecialchars($doc['image_path']) ?>" alt="<?= htmlspecialchars($doc['name']) ?>">
          </div>
          <div class="doctor-info">
            <p><strong><?= htmlspecialchars($doc['name']) ?></strong></p>
            <p><?= htmlspecialchars($doc['specialization']) ?></p>
          </div>
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
