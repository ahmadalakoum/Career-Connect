<?php

interface ApplicationRepositoryInterface
{
    public function create(array $data): bool;

    public function getApplicationById(int $id): array|null;

    public function getApplications(): array|null;

    public function deleteApplication(int $id): bool;

    public function getApplicationsByUserId(int $userId): array|null;
    public function getApplicationsByJobId(int $jobId): array|null;
}