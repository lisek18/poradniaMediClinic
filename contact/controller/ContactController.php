<?php
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';
require_once __DIR__ . '/../service/EmailSender.php';

class ContactController extends AbstractController {
    use SanitizerTrait;

    private EmailSender $mailer;
    public string $success = "";
    public string $error = "";

    public function __construct() {
        $this->mailer = new EmailSender("lasek0342@gmail.com");
    }

    public function handle(): void {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = $this->clean($_POST["name"] ?? "");
            $email = $this->clean($_POST["email"] ?? "");
            $message = $this->clean($_POST["message"] ?? "");

            if (empty($name) || empty($email) || empty($message)) {
                $this->error = "Wszystkie pola są obowiązkowe.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error = "Nieprawidłowy adres e-mail.";
            } else {
                if ($this->mailer->send($name, $email, $message)) {
                    $this->success = "Wiadomość została wysłana pomyślnie.";
                } else {
                    $this->error = "Wystąpił błąd podczas wysyłania wiadomości.";
                }
            }
        }

        require __DIR__ . '/../view/contact_view.php';
    }
}
