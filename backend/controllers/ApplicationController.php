<?php
require_once __DIR__ . "/../utils/cors.php";
require_once __DIR__ . "/../repositories/application/ApplicationRepository.php";
require_once __DIR__ . "/../utils/getBearer.php";
require_once __DIR__ . "/../repositories/user/UserRepository.php";


class ApplicationController
{
    private $applicationRepository;
    private $userRepository;

    private $jobRepository;

    public function __construct(ApplicationRepository $applicationRepository, UserRepository $userRepository, JobRepository $jobRepository)
    {
        $this->applicationRepository = $applicationRepository;
        $this->userRepository = $userRepository;
        $this->jobRepository = $jobRepository;
    }

    public function apply()
    {
        $userID = getBearerToken();
        if (!$userID) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $user = $this->userRepository->getUserById($userID);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit();
        }

        if ($user['role'] !== "user") {
            echo json_encode(['status' => 'error', 'message' => 'You cannot apply to a job as an employer']);
            exit();
        }

        if (!isset($_GET['job_id']) || empty($_GET['job_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Job ID is required']);
            exit();
        }
        $job_id = intval($_GET['job_id']);

        $job = $this->jobRepository->getJobById($job_id);
        if ($job === null) {
            echo json_encode(['status' => 'error', 'message' => 'Job not found']);
            exit();
        }
        // Handle file upload
        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 'error', 'message' => 'Resume file is required']);
            exit();
        }

        // Save resume to uploads folder
        $uploadDir = __DIR__ . "/../uploads/";
        $fileName = uniqid() . "_" . basename($_FILES['resume']['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $filePath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload resume']);
            exit();
        }

        $applicationData = [
            'job_id' => $job_id,
            'applicant_id' => $userID,
            'resume' => $fileName,
        ];

        $isCreated = $this->applicationRepository->create($applicationData);

        if ($isCreated) {
            echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Application cannot be submitted']);
        }
        exit();
    }
    public function viewApplication()
    {
        $userID = getBearerToken();
        if (!$userID) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $user = $this->userRepository->getUserById($userID);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit();
        }
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'application id not provided'
            ]);
            exit();
        }
        $application_id = intval($_GET['id']);
        $application = $this->applicationRepository->getApplicationById($application_id, $userID);
        if ($application) {
            echo json_encode([
                'status' => 'success',
                'application' => $application
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'application not found'
            ]);
            exit();
        }
    }

    public function viewAllApplications()
    {
        $userID = getBearerToken();
        if (!$userID) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $user = $this->userRepository->getUserById($userID);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit();
        }

        $applications = $this->applicationRepository->getApplications($userID);
        if ($applications) {
            echo json_encode([
                'status' => 'success',
                'applications' => $applications
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No applications found'
            ]);
            exit();
        }
    }

    public function deleteApplication()
    {
        $userID = getBearerToken();
        if (!$userID) {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit();
        }

        $user = $this->userRepository->getUserById($userID);
        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
            exit();
        }
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'application id not provided'
            ]);
            exit();
        }
        $application_id = intval($_GET['id']);
        $application = $this->applicationRepository->getApplicationById($application_id, $userID);
        if (empty($application)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'application not found'
            ]);
            exit();
        }
        $isDeleted = $this->applicationRepository->deleteApplication($application_id, $userID);
        if ($isDeleted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'application deleted successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'error deleting the application'
            ]);
            exit();
        }

    }

}