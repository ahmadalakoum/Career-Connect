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

    //function to get the job
    public function getJob()
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
        $job_id = intval($_GET['job_id']);
        if (!isset($job_id) || empty($job_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Job ID is not passed'
            ]);
            exit();
        }
        $job = $this->jobRepository->getJobById($job_id);
        if ($job !== null) {
            echo json_encode([
                'status' => 'success',
                'job' => $job
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'job is not found'
            ]);
            exit();
        }
    }
    public function getJobsEmployer()
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
        $jobs = $this->jobRepository->getJobs($userID);
        if ($jobs !== null) {
            echo json_encode([
                'status' => 'success',
                'job' => $jobs
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'jobs not found'
            ]);
            exit();
        }
    }

    public function updateJob()
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
        $job_id = intval($_GET['job_id']);
        if (!isset($job_id) || empty($job_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Job ID is not passed'
            ]);
            exit();
        }
        $job = $this->jobRepository->getJobById($job_id);
        if ($job === null) {
            echo json_encode([
                'status' => 'error',
                'message' => 'job is not found'
            ]);
            exit();
        }

        if (intval($job['employer_id']) !== intval($userID)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Cannot update a job you did not post'
            ]);
            exit();
        }
        $data = json_decode(file_get_contents("php://input"), true);
        if (empty($data)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'At least one field is required'
            ]);
            exit();
        }
        $isUpdated = $this->jobRepository->updateJob($job['id'], $userID, $data);
        if ($isUpdated) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Job updated successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error updating job'
            ]);
            exit();
        }

    }

    public function deleteJob()
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
        $job_id = intval($_GET['job_id']);
        if (!isset($job_id) || empty($job_id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Job ID is not passed'
            ]);
            exit();
        }
        $job = $this->jobRepository->getJobById($job_id);
        if ($job === null) {
            echo json_encode([
                'status' => 'error',
                'message' => 'job is not found'
            ]);
            exit();
        }

        if (intval($job['employer_id']) !== intval($userID)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Cannot delete a job you did not post'
            ]);
            exit();
        }
        $isDeleted = $this->jobRepository->deleteJob($job_id, $userID);
        if ($isDeleted) {
            echo json_encode([
                'status' => 'success',
                'message' => 'job deleted successfully'
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'error deleting the job'
            ]);
            exit();
        }
    }

    public function getAllJobs()
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
        $jobs = $this->jobRepository->getAllJobs();
        if ($jobs) {
            echo json_encode([
                'status' => 'success',
                'jobs' => $jobs
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No jobs found'
            ]);
        }
    }
    public function filter()
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


        $filterCriteria = [
            'title' => $_GET['title'] ?? null,
            'description' => $_GET['description'] ?? null,
            'category' => $_GET['category'] ?? null,
            'location' => $_GET['location'] ?? null
        ];

        $filterCriteria = array_filter($filterCriteria);
        $jobs = $this->jobRepository->filterJobs($filterCriteria);
        if ($jobs) {
            echo json_encode([
                'status' => 'success',
                'jobs' => $jobs
            ]);
            exit();
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No Jobs found'
            ]);
            exit();
        }
    }

}