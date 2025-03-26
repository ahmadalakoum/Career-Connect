<?php

interface ApplicationRepositoryInterface
{
    public function create(array $data): bool;

    public function getApplicationById(int $id, int $userID): array|null;

    public function getApplications(int $userID): array|null;

    public function deleteApplication(int $id, int $userID): bool;

    // public function getApplicationsByJobId(int $jobId): array|null;
}