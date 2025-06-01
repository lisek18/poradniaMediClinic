<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediClinic - Nowoczesna opieka medyczna</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" href="/poradnia/image/favicon.png" />
</head>
<body>

<?php include '../includes/header.php'; ?>

<section class="hero">
    <div class="container hero-content">
      <div class="hero-text">
        <h1>Nowoczesna opieka medyczna w zasięgu ręki</h1>
        <p>Kompleksowa diagnostyka i leczenie, zespół specjalistów, empatia i technologia.</p>
        <a href="/poradnia/contact/kontakt.php" class="btn primary">Umów wizytę</a>
      </div>
      <div class="hero-image">
        <img src="/poradnia/image/przychodnia.webp" alt="MediClinic - wnętrze kliniki" />
      </div>
    </div>
  </section>

  <section class="section highlight">
    <div class="container columns">
      <div class="col">
        <img src="/poradnia/image/hero-image.webp" alt="Zespół lekarzy MediClinic" />
        
      </div>
      <div class="col text">
        <h2>Dlaczego MediClinic?</h2><br>
        <ul class="icon-list">
          <li>Doświadczeni lekarze</li>
          <li>Nowoczesna diagnostyka</li>
          <li>Opieka interdyscyplinarna</li>
          <li>Dogodna lokalizacja i parking</li>
        </ul>
      </div>
    </div>
  </section>
  
 

  <section class="section services">
  <div class="container">
    <h2 class="section-title">Nasze specjalizacje</h2>
    <div class="cards">
      <div class="card">
        <h3>Kardiologia</h3>
        <p>Opieka nad sercem: EKG, echo, konsultacje.</p>
        <a href="/poradnia/department/kardiologia.php" class="btn">Dowiedz się więcej</a>
      </div>
      <div class="card">
        <h3>Ortopedia</h3>
        <p>Leczenie kontuzji, bólu stawów i rehabilitacja.</p>
        <a href="/poradnia/department/ortopedia.php" class="btn">Dowiedz się więcej</a>
      </div>
      <div class="card">
        <h3>Pediatria</h3>
        <p>Zdrowie dzieci w dobrych rękach.</p>
        <a href="/poradnia/department/pediatria.php" class="btn">Dowiedz się więcej</a>
      </div>
    </div>

    <div style="text-align: center; margin-top: 2rem;">
      <a href="/poradnia/department/services.php" class="btn">Zobacz wszystkie specjalizacje</a>
    </div>
  </div>
</section>


  <!-- CTA pełnej szerokości -->
  <section class="cta full-width">
    <div class="container cta-content">
      <h2>Umów wizytę już dziś</h2>
      <a href="/poradnia/contact/kontakt.php" class="btnn">Skontaktuj się z nami</a>
    </div>
  </section>

<!-- Opinie pacjentów -->
<section class="testimonials">
  <div class="container">
    <h2 class="section-title">Opinie pacjentów</h2>
    <div class="testimonial-slider">
      <div class="testimonial active">„Profesjonalna obsługa i nowoczesne podejście.” – Anna</div>
      <div class="testimonial">„Wizyta przebiegła sprawnie, polecam MediClinic.” – Tomasz</div>
      <div class="testimonial">„Lekarze z doświadczeniem, czysto i bezpiecznie.” – Marta</div>
    </div>
    <div class="dots">
      <span class="dot active"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>
  </div>
</section>

<!-- FAQ jako akordeon -->
<section class="faq section">
  <div class="container">
    <h2 class="section-title">Najczęściej zadawane pytania</h2>
    <div class="accordion">
      <div class="accordion-item">
        <button class="accordion-header">Jak umówić się na wizytę?</button>
        <div class="accordion-body">Możesz zadzwonić, wysłać maila lub skorzystać z formularza online.</div>
      </div>
      <div class="accordion-item">
        <button class="accordion-header">Czy można zapisać dziecko?</button>
        <div class="accordion-body">Tak – oferujemy pediatrię od pierwszych dni życia.</div>
      </div>
      <div class="accordion-item">
        <button class="accordion-header">Czy mogę odwołać lub przełożyć wizytę?</button>
        <div class="accordion-body">Tak, wizytę można odwołać lub przełożyć telefonicznie lub przez Internet, najpóźniej do 24 godzin przed jej planowanym terminem. Prosimy o wcześniejsze informowanie nas o zmianach.</div>
      </div>
      <div class="accordion-item">
        <button class="accordion-header">Jakie dokumenty powinienem zabrać na wizytę?
        </button>
        <div class="accordion-body">Należy zabrać dowód osobisty oraz dokument potwierdzający ubezpieczenie zdrowotne (np. eWUŚ). Jeśli posiadasz skierowanie, dokumentację medyczną lub wyniki badań – również warto je zabrać.

        </div>
      </div>
      <div class="accordion-item">
        <button class="accordion-header">Czy przychodnia honoruje prywatne ubezpieczenia zdrowotne?</button>
        <div class="accordion-body">Tak, współpracujemy z wybranymi firmami ubezpieczeniowymi. Lista partnerów dostępna jest w rejestracji oraz na naszej stronie internetowej.</div>
      </div>
      <div class="accordion-item">
        <button class="accordion-header">Jakie badania można wykonać na miejscu?</button>
        <div class="accordion-body">W naszej przychodni można wykonać podstawowe badania laboratoryjne, EKG, USG oraz badania diagnostyczne zlecone przez lekarza. Szczegóły dostępne są w zakładce „Usługi”.</div>
      </div>
      <div class="accordion-item">
        <button class="accordion-header">Czy można uzyskać receptę bez wizyty?</button>
        <div class="accordion-body">Tak, w uzasadnionych przypadkach możliwe jest wystawienie recepty bez wizyty. W tym celu prosimy o kontakt telefoniczny lub skorzystanie z formularza online do zamówień recept.</div>
      </div>
    </div>
  </div>
</section>

<!-- Newsletter -->
<section class="newsletter">
  <div class="container newsletter-content">
    <h2>Zapisz się do newslettera</h2>
    <p>Otrzymuj informacje o nowych usługach, promocjach i dniach otwartych.</p>

    <?= $newsletterMessage ?>

    <form action="/poradnia/newsletter/zapisz_newsletter.php" method="POST">
      <input type="email" name="email" placeholder="Twój adres e-mail" required />
      <button type="submit">Zapisz się</button>
    </form>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
<button id="scrollToTop" title="Wróć na górę">↑</button>
<script src="/poradnia/script-fixed.js"></script>
</body>
</html>
