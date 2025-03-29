<?php
require_once __DIR__ . "/ApplicationRepositoryInterface.php";
class ApplicationRepository implements ApplicationRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO applications (job_id,applicant_id,resume) VALUES (:job_id,:applicant_id,:resume)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':job_id' => $data['job_id'],
            ':applicant_id' => $data['applicant_id'],
            ':resume' => $data['resume']
        ]);
    }

    public function getApplicationById(int $id, int $userID): array|null
    {
        $sql = "SELECT applications.id as application_id,jobs.* FROM applications JOIN jobs ON applications.job_id = jobs.id WHERE applications.id = :id AND applicant_id = :userID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ":userID" => $userID
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getApplications(int $userID): array|null
    {
        $sql = "SELECT applications.id as application_id ,jobs.* FROM applications JOIN jobs ON applications.job_id=jobs.id WHERE applications.applicant_id =:userID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':userID' => $userID
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteApplication(int $id, int $userID): bool
    {
        $sql = "DELETE FROM applications WHERE id = :id and applicant_id=:userID";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":userID" => $userID
        ]);
    }

    // public function getApplicationsByJobId(int $jobId): array|null
    // {
    //     $sql = "SELECT * FROM applications WHERE job_id = :id";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute([
    //         ":id" => $jobId
    //     ]);
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }


}