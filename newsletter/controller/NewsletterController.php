<?php
require_once __DIR__ . '/../model/Database.php';
require_once __DIR__ . '/../interface/Validatable.php';
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';

class NewsletterController extends AbstractController implements Validatable {
    use SanitizerTrait;

    private PDO $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    public function handleRequest(): void {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if ($this->validate($_POST)) {
                $email = $this->sanitize($_POST['email']);
                $this->subscribe($email);
                header("Location: /poradnia/home/index.php?newsletter=success");
            } else {
                header("Location: /poradnia/home/index.php?newsletter=error");
            }
            exit;
        }
    }

    public function validate(array $data): bool {
        return isset($data['email']) && filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);
    }

    private function subscribe(string $email): void {
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO newsletter_subscribers (email) VALUES (:email)");
        $stmt->execute(['email' => $email]);
    }
}
