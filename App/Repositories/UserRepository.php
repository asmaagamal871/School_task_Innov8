<?php

namespace App\Repositories;

use Config\Database;
use App\Models\User;
use DateTime;
use PDO;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function save(User $user): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        return $stmt->execute([$user->username, $user->email, $user->password]);
    }

    public function saveUserToken(string $selector, string $hashedValidator, int $userId, DateTime $expiry): void
    {
        $stmt = $this->pdo->prepare("
        INSERT INTO user_tokens (selector, hashed_validator, user_id, expiry_date)
        VALUES (:selector, :hashed_validator, :user_id, :expiry_date)
    ");

        $stmt->execute([
            ':selector' => $selector,
            ':hashed_validator' => $hashedValidator,
            ':user_id' => $userId,
            ':expiry_date' => $expiry->format('Y-m-d H:i:s')
        ]);
    }

    public function getUserToken(string $selector): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user_tokens WHERE selector = :selector LIMIT 1");
        $stmt->execute([':selector' => $selector]);
        $token = $stmt->fetch();

        return $token ?: null;
    }

    public function deleteToken(string $selector): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_tokens WHERE selector = :selector");
        $stmt->execute([':selector' => $selector]);
    }

    public function getUserById(int $userId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE user_id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetch();
    }

    public function deleteUserTokens(int $userId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_tokens WHERE user_id = ?");
        $stmt->execute([$userId]);
    }

}
