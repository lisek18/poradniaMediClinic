<?php
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../model/VisitService.php';
require_once __DIR__ . '/../model/UploadService.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';

class EditVisitController extends AbstractController {
    use SanitizerTrait;

    public function handle(): void {
        session_start();

        $visitId = (int)($_GET['id'] ?? 0);
        $pdo = new PDO("mysql:host=localhost;dbname=mediclinic;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $visitService = new VisitService($pdo);
        $uploadService = new UploadService($pdo);

        $visit = $visitService->getVisit($visitId);
        $services = $visitService->getServices($visit['department_name']);

        $success = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notes = $this->clean($_POST['visit_notes'] ?? '');
            $recommend = $this->clean($_POST['recommendations'] ?? '');
            $serviceId = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;

            $ok = $visitService->updateVisit($visitId, [
                'visit_notes' => $notes,
                'recommendations' => $recommend,
                'service_id' => $serviceId
            ]);

            if (!empty($_FILES['document']['name'])) {
                $result = $uploadService->upload($visit['patient_id'], $_FILES['document']);
                if (!$result) $error = "Nie udało się zapisać pliku.";
            }

            if (empty($error) && $ok) $success = "Dane wizyty zostały zaktualizowane.";
        }

        require __DIR__ . '/../view/edit_visit_view.php';
    }
}
