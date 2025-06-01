<?php
class VisitService {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getVisitsByDoctor(int $doctorId): array {
        $stmt = $this->pdo->prepare("
            SELECT v.id, v.visit_datetime, p.first_name, p.last_name
            FROM visits v
            JOIN patients p ON v.patient_id = p.id
            WHERE v.doctor_id = :doctorId
            ORDER BY v.visit_datetime DESC
        ");
        $stmt->execute(['doctorId' => $doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
