<?php
require_once __DIR__ . '/../interface/Updatable.php';

class VisitService implements Updatable {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getVisit(int $visitId): array {
        $stmt = $this->pdo->prepare("
            SELECT v.*, p.first_name, p.last_name, p.id AS patient_id, dep.name AS department_name 
            FROM visits v 
            JOIN patients p ON v.patient_id = p.id 
            JOIN departments dep ON v.department_id = dep.id 
            WHERE v.id = :id
        ");
        $stmt->execute(['id' => $visitId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getServices(string $department): array {
        $table = strtolower($department);
        $table = str_replace(['ł','ś','ż','ź','ć','ę','ó','ń','ą'], ['l','s','z','z','c','e','o','n','a'], $table);
        $tableName = "{$table}_services";

        $stmt = $this->pdo->query("SELECT id, service_name, price FROM `$tableName`");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateVisit(int $visitId, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE visits SET visit_notes = ?, recommendations = ?, service_id = ? WHERE id = ?
        ");
        return $stmt->execute([
            $data['visit_notes'],
            $data['recommendations'],
            $data['service_id'],
            $visitId
        ]);
    }
}
