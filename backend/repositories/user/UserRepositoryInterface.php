<?php

interface UserRepositoryInterface
{
    public function createUser(array $userData): bool;
    public function getUserById(int $userId): ?array;
    public function getUserByEmail(string $email): ?array;
    public function updateUser(int $userId, array $updateData): bool;
    public function deleteUser(int $userId): bool;
    public function validateUserCredentials(string $email, string $password): ?array;
    public function updatePassword(int $userId, string $newPassword): bool;
    public function isEmailTaken(string $email): bool;
    public function isUsernameTaken(string $username): bool;
}