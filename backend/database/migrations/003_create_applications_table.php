<?php

class CreateApplicationsTable
{
    public function up($pdo)
    {
        $sql = "CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        job_id INT NOT NULL,
        applicant_id INT NOT NULL,
        resume VARCHAR(255) NOT NULL,
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (applicant_id) REFERENCES users(id) on DELETE CASCADE ,
        FOREIGN KEY (job_id) REFERENCES jobs(id) on DELETE CASCADE 
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }

    public function down($pdo)
    {
        $sql = "DROP TABLE IF EXISTS applications";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    }
}