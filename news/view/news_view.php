<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Aktualności – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" href="/poradnia/image/favicon.png" />
  <style>
    .news-container {
      max-width: 1100px;
      margin: 4rem auto;
      padding: 2rem;
    }
    .news-container h2 {
      color: #0a9396;
      text-align: center;
      margin-bottom: 2rem;
    }
    .news-item {
      border-bottom: 1px solid #ccc;
      padding: 1.5rem 0;
    }
    .news-item h3 {
      color: #005f73;
      margin-bottom: 0.5rem;
    }
    .news-item .date {
      font-size: 0.9rem;
      color: #777;
      margin-bottom: 0.5rem;
    }
    .news-item p {
      font-size: 1rem;
      line-height: 1.5;
    }

    /* Scroll button styling */
    #scrollToTop {
      display: none;
      position: fixed;
      bottom: 30px;
      right: 30px;
      padding: 10px 15px;
      font-size: 20px;
      background-color: #0a9396;
      color: white;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      z-index: 999;
    }
    #scrollToTop:hover {
      background-color: #007f7f;
    }
  </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="news-container">
  <h2>Aktualności z poradni</h2>

  <?php foreach ($news as $item): ?>
    <div class="news-item">
      <h3><?= $item['title'] ?></h3>
      <div class="date"><?= $item['published_at'] ?></div>
      <p><?= $item['content'] ?></p>
    </div>
  <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>

<button id="scrollToTop" title="Wróć na górę">↑</button>
<script src="/poradnia/script-fixed.js"></script>

</body>
</html>
