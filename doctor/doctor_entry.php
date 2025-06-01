<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Panel lekarza – MediClinic</title>
  <link rel="stylesheet" href="/poradnia/css/style.css" />
  <link rel="icon" type="image/png" href="image/favicon.png" />
  <style>
    .doctor-entry-container {
      max-width: 900px;
      margin: auto;
      padding: 3rem 1rem;
      text-align: center;
    }
    .doctor-entry-container h2 {
      color: #0a9396;
      margin-bottom: 2rem;
    }
    .doctor-buttons {
      display: flex;
      justify-content: center;
      gap: 2rem;
      flex-wrap: wrap;
    }
    .doctor-buttons a {
      background: #0a9396;
      color: white;
      padding: 1rem 2rem;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }
    .doctor-buttons a:hover {
      background: #007f7f;
    }
  </style>
</head>
<body>

<div class="doctor-entry-container">
  <h2>Panel lekarza</h2>
  <div class="doctor-buttons">
    <a href="/poradnia/doctor/login_doctor.php">Zaloguj się jako lekarz</a>
    <a href="/poradnia/doctor/register_doctor.php">Zarejestruj się jako lekarz</a>
  </div>
</div>

</body>
</html>
