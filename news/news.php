<?php
require_once __DIR__ . '/controller/NewsController.php';

$pdo = new PDO("mysql:host=localhost;dbname=mediclinic;charset=utf8mb4", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$model = new NewsModel($pdo);
$controller = new NewsController($model);
$controller->handle();
