<?php

declare(strict_types=1);

require_once __DIR__ . '/../../middleware/role.php';
require_once __DIR__ . '/../../services/ExamService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => API_ERROR, 'message' => 'Method not allowed'], 405);
}

require_backend_role(ROLE_STAFF, ROLE_ADMIN);

$examId = (int) ($_POST['exam_id'] ?? 0);
$questionText = trim((string) ($_POST['question_text'] ?? ''));
$questionType = (string) ($_POST['question_type'] ?? 'MCQ');
$correctAnswer = trim((string) ($_POST['correct_answer'] ?? ''));
$marks = (int) ($_POST['marks'] ?? 1);

if ($examId <= 0 || $questionText === '' || $correctAnswer === '' || $marks <= 0) {
    json_response(['status' => API_ERROR, 'message' => 'Invalid question payload.'], 422);
}

$questionId = (new ExamService())->addQuestion(
    $examId,
    $questionText,
    $questionType,
    $correctAnswer,
    $marks,
    [
        'a' => $_POST['option_a'] ?? null,
        'b' => $_POST['option_b'] ?? null,
        'c' => $_POST['option_c'] ?? null,
        'd' => $_POST['option_d'] ?? null,
    ]
);

json_response([
    'status' => API_OK,
    'message' => 'Question added successfully.',
    'data' => ['question_id' => $questionId],
], 201);
