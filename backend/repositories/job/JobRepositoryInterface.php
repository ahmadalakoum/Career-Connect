<?php
interface JobRepositoryInterface
{
    public function create(array $data): bool;
    public function getJobById(int $id): ?array;
    public function getJobs(int $employer_id): ?array;
    public function updateJob(int $id, int $employer_id, array $updateData): bool;
    public function deleteJob(int $id, int $employer_id): bool;

    // user
    public function getAllJobs(): ?array;

    public function filterJobs($filter): ?array;

}