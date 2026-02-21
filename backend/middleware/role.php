<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function require_backend_role(string ...$roles): array
{
    $user = require_backend_auth();

    if (!in_array($user['role'], $roles, true)) {
        json_response([
            'status' => API_ERROR,
            'message' => 'Forbidden: insufficient role permissions.',
        ], 403);
    }

    return $user;
}
