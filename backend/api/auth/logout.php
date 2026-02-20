<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

require_backend_auth();
unset($_SESSION[SESSION_USER_KEY]);

json_response([
    'status' => API_OK,
    'message' => 'Logout successful.',
]);
