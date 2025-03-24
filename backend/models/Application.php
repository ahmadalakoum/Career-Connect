<?php

class Application
{
    private int $id;
    private int $job_id;
    private int $applicant_id;
    private string $resume;

    // Constructor to initialize attributes
    public function __construct(int $id, int $job_id, int $applicant_id, string $resume)
    {
        $this->id = $id;
        $this->job_id = $job_id;
        $this->applicant_id = $applicant_id;
        $this->resume = $resume;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getJobId(): int
    {
        return $this->job_id;
    }

    public function getApplicantId(): int
    {
        return $this->applicant_id;
    }

    public function getResume(): string
    {
        return $this->resume;
    }

    // Setters
    public function setJobId(int $job_id): void
    {
        $this->job_id = $job_id;
    }

    public function setApplicantId(int $applicant_id): void
    {
        $this->applicant_id = $applicant_id;
    }

    public function setResume(string $resume): void
    {
        $this->resume = $resume;
    }
}
