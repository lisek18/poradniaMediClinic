<?php
require_once __DIR__ . '/../interface/Loadable.php';

class NewsModel implements Loadable {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function loadData(): array {
        $stmt = $this->pdo->query("SELECT title, content, published_at FROM news ORDER BY published_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsSeen(int $patientId): void {
        $stmt = $this->pdo->query("SELECT MAX(id) as max_id FROM news");
        $maxId = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'] ?? 0;

        $update = $this->pdo->prepare("UPDATE patients SET last_seen_news_id = :max_id WHERE id = :patient_id");
        $update->execute(['max_id' => $maxId, 'patient_id' => $patientId]);
    }
}
