<?php
require_once __DIR__ . '/controller/NewsletterController.php';

$controller = new NewsletterController();
$controller->handleRequest();
