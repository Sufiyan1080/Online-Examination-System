<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/role.php';
require_once __DIR__ . '/../../services/ResultService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

$student = require_backend_role(ROLE_STUDENT);
$assignmentId = (int) ($_POST['assignment_id'] ?? 0);
$answers = $_POST['answers'] ?? [];

if ($assignmentId <= 0 || !is_array($answers)) {
    json_response(['status' => API_ERROR, 'message' => 'assignment_id and answers[] are required'], 422);
}

try {
    $result = (new ResultService())->submitExam($assignmentId, (int) $student['id'], $answers);
} catch (RuntimeException $exception) {
    json_response(['status' => API_ERROR, 'message' => $exception->getMessage()], 404);
}

json_response([
    'status' => API_OK,
    'message' => 'Exam submitted successfully.',
    'data' => $result,
]);
