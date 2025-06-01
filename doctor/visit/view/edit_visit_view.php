<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Edycja wizyty</title>
  <link rel="stylesheet" href="/poradnia/css/style.css">
</head>
<body>

<?php include '../header.php'; ?>

<div class="container" style="max-width: 900px; margin-top: 3rem;">
  <h2>Wizyta: <?= htmlspecialchars($visit['first_name'] . ' ' . $visit['last_name']) ?> (<?= $visit['visit_datetime'] ?>)</h2>

  <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

  <form method="POST" enctype="multipart/form-data" style="margin-top:2rem;">
    <label>Wykonane badanie:<br>
      <select name="service_id">
        <option value="">-- wybierz badanie --</option>
        <?php foreach ($services as $srv): ?>
          <option value="<?= $srv['id'] ?>" <?= ($visit['service_id'] == $srv['id'] ? 'selected' : '') ?>>
            <?= htmlspecialchars($srv['service_name']) ?> (<?= $srv['price'] ?> zł)
          </option>
        <?php endforeach; ?>
      </select>
    </label><br><br>

    <label>Opis wizyty:<br>
      <textarea name="visit_notes" rows="5" style="width:100%;"><?= htmlspecialchars($visit['visit_notes']) ?></textarea>
    </label><br><br>

    <label>Zalecenia:<br>
      <textarea name="recommendations" rows="4" style="width:100%;"><?= htmlspecialchars($visit['recommendations']) ?></textarea>
    </label><br><br>

    <label>Dodaj dokument:
      <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.docx,.txt">
    </label><br><br>

    <button type="submit" class="btn">Zapisz</button>
  </form>
  <a href="/poradnia/doctor/panel/panel.php" class="btn" style="margin-top:10px; margin-bottom:2em;">← Wróć do panelu</a>
</div>

<?php include '../../includes/footer.php'; ?>
<script src="/poradnia/script-fixed.js"></script>
<button id="scrollToTop" title="Wróć na górę">↑</button>

</body>
</html>
