<?php

require_once __DIR__ . "/JobRepositoryInterface.php";
require_once __DIR__ . "/../../connection/connection.php";
class JobRepository implements JobRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // employer functionalities
    public function create(array $data): bool
    {
        $sql = "INSERT INTO jobs (employer_id,title,description,salary,category,location,deadline) VALUES (:employer_id,:title,:description,:salary,:category,:location,:deadline)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':employer_id' => $data["employer_id"],
            ':title' => $data["title"],
            ":description" => $data["description"],
            ":salary" => $data["salary"],
            ":category" => $data["category"],
            ":location" => $data["location"],
            ":deadline" => $data['deadline']
        ]);
    }
    public function getJobById(int $id): array|null
    {
        $sql = "SELECT * FROM jobs WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getJobs(int $employer_id): array|null
    {
        $sql = "SELECT * FROM jobs WHERE employer_id= :employer_id ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':employer_id' => $employer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateJob(int $id, int $employer_id, array $updateData): bool
    {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($updateData)));

        $sql = "UPDATE jobs SET $setClause WHERE id = :id AND employer_id=:employer_id";
        $stmt = $this->pdo->prepare($sql);
        $updateData['id'] = $id;
        $updateData['employer_id'] = $employer_id;
        return $stmt->execute($updateData);
    }

    public function deleteJob(int $id, int $employer_id): bool
    {
        $sql = "DELETE FROM jobs WHERE id=:id AND employer_id= :employer_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ":employer_id" => $employer_id
        ]);
    }

    // user functionality
    public function getAllJobs(): array|null
    {
        $sql = "SELECT * FROM jobs ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filterJobs($filter): array|null
    {
        $sql = "SELECT * FROM jobs WHERE 1";
        $params = [];

        if (!empty($filter['title'])) {
            $sql .= " AND title LIKE :title";
            $params[':title'] = "%" . $filter['title'] . "%";
        }
        if (!empty($filter['description'])) {
            $sql .= " AND description LIKE :description";
            $params[':description'] = "%" . $filter['description'] . "%";
        }
        if (!empty($filter['category'])) {
            $sql .= " AND category = :category";
            $params[':category'] = $filter['category'];
        }
        if (!empty($filter['location'])) {
            $sql .= " AND location = :location";
            $params[':location'] = $filter['location'];
        }

        // Prepare and execute the query
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Fetch results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




}
