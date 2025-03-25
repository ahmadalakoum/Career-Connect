<?php

require_once __DIR__ . "/../repositories/job/JobRepository.php";
require_once __DIR__ . "/../utils/getBearer.php";
require_once __DIR__ . "/../repositories/user/UserRepository.php";
class JobController
{
    private $jobRepository;
    private $userRepository;

    public function __construct(JobRepository $jobRepository, UserRepository $userRepository)
    {
        $this->jobRepository = $jobRepository;
        $this->userRepository = $userRepository;
    }

    //function to add a new job
    public function addJob()
    {
        $userID = getBearerToken();
        if (!$userID) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Unauthorized'
            ]);
            exit();
        }
        $user = $this->userRepository->getUserById($userID);
        if (!$user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'User not found'
            ]);
            exit();
        }
        $role = $user['role'];
        if ($role !== 'employer') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Permission denied'
            ]);
            exit();
        }
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'No Data Received'
            ]);
            exit();
        }
        $data['employer_id'] = $userID;
        $title = trim($data['title']);
        $description = trim($data['description']);
        $salary = trim($data['salary']);
        $category = trim($data['category']);
        $deadline = trim($data['deadline']);
        $location = trim($data['location']);

        if (empty($title) || empty($description) || empty($salary) || empty($category) || empty($deadline) || empty($location)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'All fields are required'
            ]);
            exit();
        }
        $added = $this->jobRepository->create($data);
        if ($added) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Job added successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'failed to add the job'
            ]);
            exit();
        }
    }
}