<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/auth.php';
require_once __DIR__ . '/../../services/AuthService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? '');

if ($email === '' || $password === '') {
    json_response(['status' => API_ERROR, 'message' => 'Email and password are required.'], 422);
}

$service = new AuthService();
$user = $service->login($email, $password);

if (!$user) {
    json_response(['status' => API_ERROR, 'message' => 'Invalid credentials.'], 401);
}

$_SESSION[SESSION_USER_KEY] = $user;

json_response([
    'status' => API_OK,
    'message' => 'Login successful.',
    'data' => $user,
]);
