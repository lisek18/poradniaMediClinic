<?php
class PatientService {
    private PDO $pdo;
    private int $doctorId;

    public function __construct(PDO $pdo, int $doctorId) {
        $this->pdo = $pdo;
        $this->doctorId = $doctorId;
    }

    public function getPatients(): array {
        $stmt = $this->pdo->prepare("
            SELECT DISTINCT p.id, p.first_name, p.last_name
            FROM patients p
            JOIN messages m ON (
              (m.sender_id = p.id AND m.receiver_id = :doc)
              OR (m.receiver_id = p.id AND m.sender_id = :doc)
            )
        ");
        $stmt->execute(['doc' => $this->doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
