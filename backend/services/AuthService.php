<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/constants.php';

class AuthService
{
    public function login(string $email, string $password): ?array
    {
        $stmt = backend_db()->prepare('SELECT id, full_name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, (string) $user['password'])) {
            return null;
        }

        return [
            'id' => (int) $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    public function createUser(string $fullName, string $email, string $password, string $role): int
    {
        $stmt = backend_db()->prepare(
            'INSERT INTO users (full_name, email, password, role, created_at) VALUES (:full_name, :email, :password, :role, NOW())'
        );
        $stmt->execute([
            'full_name' => $fullName,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
        ]);

        return (int) backend_db()->lastInsertId();
    }
}
