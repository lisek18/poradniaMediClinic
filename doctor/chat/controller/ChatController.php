<?php
require_once __DIR__ . '/../abstract/AbstractController.php';
require_once __DIR__ . '/../traits/SanitizerTrait.php';
require_once __DIR__ . '/../model/MessageService.php';
require_once __DIR__ . '/../model/PatientService.php';

class ChatController extends AbstractController {
    use SanitizerTrait;

    public function handle(): void {
        session_start();

        if (!isset($_SESSION['doctor_id'])) {
            header("Location: ../login_doctor.php");
            exit;
        }

        $doctorId = $_SESSION['doctor_id'];
        $pdo = new PDO("mysql:host=localhost;dbname=mediclinic;charset=utf8mb4", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $patientId = isset($_GET['patient_id']) ? (int)$_GET['patient_id'] : null;
        $messageService = new MessageService($pdo);
        $patientService = new PatientService($pdo, $doctorId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $patientId) {
            $msg = $this->clean($_POST['message'] ?? '');
            if (!empty($msg)) {
                $messageService->sendMessage($doctorId, $patientId, $msg);
                header("Location: ?patient_id=$patientId");
                exit;
            }
        }

        $patients = $patientService->getPatients();
        $messages = $patientId ? $messageService->getChatHistory($doctorId, $patientId) : [];

        require __DIR__ . '/../view/chat_view.php';
    }
}
