<?php

function config(string $key)
{
    static $config;
    if (!$config) {
        $config = require __DIR__ . '/config/config.php';
    }

    $segments = explode('.', $key);
    $value = $config;

    foreach ($segments as $segment) {
        if (!isset($value[$segment])) {
            return null;
        }
        $value = $value[$segment];
    }

    return $value;
}

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            config('db.host'),
            config('db.port'),
            config('db.database'),
            config('db.charset')
        );

        $pdo = new PDO($dsn, config('db.username'), config('db.password'), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}

function redirect(string $path): void
{
    header('Location: ' . config('app.base_url') . '/index.php?page=' . ltrim($path, '?'));
    exit;
}

function render(string $view, array $data = []): void
{
    extract($data);
    $viewPath = __DIR__ . '/views/' . $view . '.php';
    require __DIR__ . '/views/layouts/app.php';
}

function post(string $key, $default = null)
{
    return $_POST[$key] ?? $default;
}

function session_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function require_auth(?string $role = null): void
{
    if (!session_user()) {
        redirect('login');
    }
    if ($role && session_user()['role'] !== $role) {
        http_response_code(403);
        die('Access denied');
    }
}

function flash(?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'] = $message;
        return null;
    }

    $msg = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $msg;
}
