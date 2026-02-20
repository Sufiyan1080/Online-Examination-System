<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

class ExamService
{
    public function createExam(string $title, string $subject, int $durationMinutes, int $totalMarks, int $staffId): int
    {
        $stmt = backend_db()->prepare(
            'INSERT INTO exams (title, subject, topic, duration_minutes, total_marks, teacher_id, created_at)
             VALUES (:title, :subject, :topic, :duration_minutes, :total_marks, :teacher_id, NOW())'
        );

        $stmt->execute([
            'title' => $title,
            'subject' => $subject,
            'topic' => $subject,
            'duration_minutes' => $durationMinutes,
            'total_marks' => $totalMarks,
            'teacher_id' => $staffId,
        ]);

        return (int) backend_db()->lastInsertId();
    }

    public function addQuestion(int $examId, string $questionText, string $questionType, string $correctAnswer, int $marks, array $options = []): int
    {
        $stmt = backend_db()->prepare(
            'INSERT INTO questions (exam_id, question_text, question_type, option_a, option_b, option_c, option_d, correct_answer, marks)
             VALUES (:exam_id, :question_text, :question_type, :option_a, :option_b, :option_c, :option_d, :correct_answer, :marks)'
        );

        $stmt->execute([
            'exam_id' => $examId,
            'question_text' => $questionText,
            'question_type' => $questionType,
            'option_a' => $options['a'] ?? null,
            'option_b' => $options['b'] ?? null,
            'option_c' => $options['c'] ?? null,
            'option_d' => $options['d'] ?? null,
            'correct_answer' => $correctAnswer,
            'marks' => $marks,
        ]);

        return (int) backend_db()->lastInsertId();
    }

    public function startExam(int $examId, int $studentId): int
    {
        $stmt = backend_db()->prepare(
            'INSERT INTO exam_assignments (exam_id, student_id, schedule_at, status) VALUES (:exam_id, :student_id, NOW(), :status)'
        );
        $stmt->execute([
            'exam_id' => $examId,
            'student_id' => $studentId,
            'status' => 'in_progress',
        ]);

        return (int) backend_db()->lastInsertId();
    }
}
