<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/role.php';
require_once __DIR__ . '/../../services/ExamService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

$staff = require_backend_role(ROLE_STAFF, ROLE_ADMIN);

$title = trim((string) ($_POST['title'] ?? ''));
$subject = trim((string) ($_POST['subject'] ?? ''));
$duration = (int) ($_POST['duration_minutes'] ?? 0);
$totalMarks = (int) ($_POST['total_marks'] ?? 0);

if ($title === '' || $subject === '' || $duration <= 0 || $totalMarks <= 0) {
    json_response(['status' => API_ERROR, 'message' => 'Invalid exam payload.'], 422);
}

$examId = (new ExamService())->createExam($title, $subject, $duration, $totalMarks, (int) $staff['id']);

json_response([
    'status' => API_OK,
    'message' => 'Exam created successfully.',
    'data' => ['exam_id' => $examId],
], 201);
