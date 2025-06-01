<?php
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../service/NewsletterHandler.php';

class HomeController extends AbstractController {
    public function handle(): void {
        session_start();

        $newsletterHandler = new NewsletterHandler();
        $newsletterMessage = $newsletterHandler->getMessage();

        require __DIR__ . '/../view/index_view.php';
    }
}
