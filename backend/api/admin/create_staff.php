<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/role.php';
require_once __DIR__ . '/../../services/AuthService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

require_backend_role(ROLE_ADMIN);

$fullName = trim((string) ($_POST['full_name'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$password = (string) ($_POST['password'] ?? 'staff123');

if ($fullName === '' || $email === '') {
    json_response(['status' => API_ERROR, 'message' => 'full_name and email are required'], 422);
}

$id = (new AuthService())->createUser($fullName, $email, $password, ROLE_STAFF);

json_response([
    'status' => API_OK,
    'message' => 'Staff created successfully.',
    'data' => ['id' => $id],
], 201);
