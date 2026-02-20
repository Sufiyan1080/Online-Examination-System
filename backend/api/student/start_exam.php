<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/role.php';
require_once __DIR__ . '/../../services/ExamService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

$student = require_backend_role(ROLE_STUDENT);
$examId = (int) ($_POST['exam_id'] ?? 0);

if ($examId <= 0) {
    json_response(['status' => API_ERROR, 'message' => 'exam_id is required'], 422);
}

$assignmentId = (new ExamService())->startExam($examId, (int) $student['id']);

json_response([
    'status' => API_OK,
    'message' => 'Exam started.',
    'data' => ['assignment_id' => $assignmentId],
], 201);
