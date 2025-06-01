<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Aktualności – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" href="/poradnia/image/favicon.png" />
</head><body>
<?php include '../includes/header.php'; ?>

<section class="section contact-section">
  <div class="container contact-container">
    <div class="contact-info">
      <h1 style="color: #005f73; font-size: 2.75rem;">Skontaktuj się z nami</h1>
      <p>Masz pytania? Wypełnij formularz lub skorzystaj z danych kontaktowych.</p>
      <ul>
        <li><strong>Adres:</strong> ul. Zdrowia 12, 00-001 Warszawa</li>
        <li><strong>Telefon:</strong> <a href="tel:+48123456789">123 456 789</a></li>
        <li><strong>Email:</strong> <a href="mailto:kontakt@mediclinic.pl">kontakt@mediclinic.pl</a></li>
        <li><strong>Godziny otwarcia:</strong><br> Pon–Pt 8:00–18:00</li>
      </ul>
    </div>

    <div class="contact-form">
      <h2>Wypełnij formularz</h2><br>
      <?php if ($error): ?><div style="color: red; margin-bottom: 10px;"><?= $error ?></div><?php endif; ?>
      <?php if ($success): ?><div style="color: green; margin-bottom: 10px;"><?= $success ?></div><?php endif; ?>

      <form action="kontakt.php" method="POST" novalidate>
        <label for="name">Imię i nazwisko</label>
        <input type="text" id="name" name="name" required />

        <label for="email">Adres e-mail</label>
        <input type="email" id="email" name="email" required />

        <label for="message">Wiadomość</label>
        <textarea id="message" name="message" rows="6" required></textarea>

        <button type="submit" class="btn">Wyślij wiadomość</button>
      </form>
    </div>
  </div>
</section>

<div class="map-container" style="margin-top: 2rem;">
  <iframe width="100%" height="400" frameborder="0" 
    src="https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=NZOZ%20%22Przychodnia%20Brze%C5%BAno%22%20Sp.%20z%20o.o.+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
</div>

<?php include '../includes/footer.php'; ?>

<button id="scrollToTop" title="Wróć na górę">↑</button>
<script src="/poradnia/script-fixed.js"></script>
      </body>
      </html>