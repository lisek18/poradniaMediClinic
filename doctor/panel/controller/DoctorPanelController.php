<?php
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../model/VisitService.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';

class DoctorPanelController extends AbstractController {
    use SanitizerTrait;

    public function handle(): void {
        session_start();

        if (!isset($_SESSION['doctor_logged_in']) || $_SESSION['doctor_logged_in'] !== true) {
            header("Location: ../login_doctor.php");
            exit;
        }

        $doctorId = $_SESSION['doctor_id'];

        $pdo = new PDO("mysql:host=localhost;dbname=mediclinic;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $visitService = new VisitService($pdo);
        $visits = $visitService->getVisitsByDoctor($doctorId);

        require __DIR__ . '/../view/doctor_panel_view.php';
    }
}
