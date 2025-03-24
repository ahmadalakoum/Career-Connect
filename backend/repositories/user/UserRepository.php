<?php

require_once __DIR__ . "/UserRepositoryInterface.php";
require_once __DIR__ . "/../../connection/connection.php";
class UserRepository implements UserRepositoryInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function createUser(array $userData): bool
    {
        $sql = "INSERT INTO users (username,email,password) VALUES (:username,:email,:password)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ":username" => $userData['username'],
            ":email" => $userData['email'],
            ":password" => password_hash($userData['password'], PASSWORD_BCRYPT)
        ]);
    }

    public function getUserById(int $userId): array|null
    {
        $sql = "SELECT * FROM users WHERE id = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUserByEmail(string $email): array|null
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getUserByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function updateUser(int $userId, array $updateData): bool
    {
        $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($updateData)));
        $sql = "UPDATE users SET $setClause WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $updateData['id'] = $userId;
        return $stmt->execute($updateData);
    }

    public function deleteUser(int $userId): bool
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $userId]);
    }

    public function validateUserCredentials(string $email, string $password): ?array
    {
        $user = $this->getUserByEmail($email);
        return ($user && password_verify($password, $user['password'])) ? $user : null;
    }
    public function updatePassword(int $userId, string $newPassword): bool
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['password' => $hashedPassword, 'id' => $userId]);
    }
    public function isEmailTaken(string $email): bool
    {
        return $this->getUserByEmail($email) !== null;
    }

    public function isUsernameTaken(string $username): bool
    {
        return $this->getUserByUsername($username) !== null;
    }
}