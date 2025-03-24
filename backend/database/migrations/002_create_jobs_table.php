<?php

class CreateJobsTable
{
    public function up($pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        employer_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        salary DECIMAL(10,2) DEFAULT NULL,
        category VARCHAR(255) NOT NULL,
        location VARCHAR(255) NOT NULL,
        deadline DATE NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (employer_id) REFERENCES users(id) on DELETE CASCADE 
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    public function down($pdo)
    {
        $sql = "DROP TABLE IF EXISTS jobs";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}