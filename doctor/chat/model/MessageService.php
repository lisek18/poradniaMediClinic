<?php
class MessageService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function sendMessage(int $doctorId, int $patientId, string $message): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (sender_id, receiver_id, sender_role, message)
            VALUES (?, ?, 'doctor', ?)
        ");
        $stmt->execute([$doctorId, $patientId, $message]);
    }

    public function getChatHistory(int $doctorId, int $patientId): array {
        $stmt = $this->pdo->prepare("
            SELECT * FROM messages
            WHERE (sender_id = ? AND receiver_id = ?)
               OR (sender_id = ? AND receiver_id = ?)
            ORDER BY sent_at ASC
        ");
        $stmt->execute([$doctorId, $patientId, $patientId, $doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
