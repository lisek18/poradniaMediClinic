<?php
class NewsletterHandler {
    public function getMessage(): string {
        if (isset($_GET['newsletter'])) {
            if ($_GET['newsletter'] === 'success') {
                return '<p style="color:green;">Dziękujemy za zapis do newslettera!</p>';
            } elseif ($_GET['newsletter'] === 'error') {
                return '<p style="color:red;">Nieprawidłowy adres e-mail.</p>';
            }
        }
        return '';
    }
}
