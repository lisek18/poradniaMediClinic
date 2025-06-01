<?php
require_once __DIR__ . '/../interface/Emailable.php';

class EmailSender implements Emailable {
    private string $recipient;

    public function __construct(string $recipientEmail) {
        $this->recipient = $recipientEmail;
    }

    public function send(string $name, string $email, string $message): bool {
        $subject = "Nowa wiadomość z formularza MediClinic";
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

        $body = "Imię i nazwisko: $name\nEmail: $email\n\nWiadomość:\n$message";

        return mail($this->recipient, $subject, $body, $headers);
    }
}
