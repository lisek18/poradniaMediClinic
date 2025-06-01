<?php
require_once __DIR__ . '/../interface/Uploadable.php';

class UploadService implements Uploadable {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function upload(int $patientId, array $file): ?string {
        $uploadDir = __DIR__ . "/../../../uploads/patient_$patientId/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $relativePath = "/poradnia/uploads/patient_$patientId/" . $fileName;
        $targetPath = $uploadDir . $fileName;
        $fileType = mime_content_type($file['tmp_name']);

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $this->pdo->prepare("
                INSERT INTO medical_documents (patient_id, file_name, file_path, file_type) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$patientId, $fileName, $relativePath, $fileType]);
            return $relativePath;
        }

        return null;
    }
}
