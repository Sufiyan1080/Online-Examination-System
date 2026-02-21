<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/constants.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function json_response(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function current_backend_user(): ?array
{
    return $_SESSION[SESSION_USER_KEY] ?? null;
}

function require_backend_auth(): array
{
    $user = current_backend_user();
    if (!$user) {
        json_response([
            'status' => API_ERROR,
            'message' => 'Authentication required.',
        ], 401);
    }

    return $user;
}
