<?php

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

    public function getApplicationById(int $id): array|null
    {
        $sql = "SELECT * FROM applications WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getApplications(): array|null
    {
        $sql = "SELECT * FROM applications";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteApplication(int $id): bool
    {
        $sql = "DELETE FROM applications WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":id" => $id
        ]);
    }

    public function getApplicationsByUserId(int $userId): array|null
    {
        $sql = "SELECT * FROM applications WHERE applicant_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id" => $userId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getApplicationsByJobId(int $jobId): array|null
    {
        $sql = "SELECT * FROM applications WHERE job_id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ":id" => $jobId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}