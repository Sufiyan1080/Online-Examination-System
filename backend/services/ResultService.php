<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

class ResultService
{
    public function submitExam(int $assignmentId, int $studentId, array $answers): array
    {
        $examStmt = backend_db()->prepare('SELECT exam_id FROM exam_assignments WHERE id = :id AND student_id = :student_id LIMIT 1');
        $examStmt->execute([
            'id' => $assignmentId,
            'student_id' => $studentId,
        ]);
        $assignment = $examStmt->fetch();

        if (!$assignment) {
            throw new RuntimeException('Assignment not found.');
        }

        $qStmt = backend_db()->prepare('SELECT id, correct_answer, marks FROM questions WHERE exam_id = :exam_id');
        $qStmt->execute(['exam_id' => (int) $assignment['exam_id']]);
        $questions = $qStmt->fetchAll();

        $score = 0;
        $maxScore = 0;

        foreach ($questions as $question) {
            $maxScore += (int) $question['marks'];
            $qid = (int) $question['id'];
            if (($answers[(string) $qid] ?? null) === $question['correct_answer']) {
                $score += (int) $question['marks'];
            }
        }

        $insert = backend_db()->prepare(
            'INSERT INTO results (assignment_id, student_id, score, feedback, submitted_at)
             VALUES (:assignment_id, :student_id, :score, :feedback, NOW())'
        );
        $insert->execute([
            'assignment_id' => $assignmentId,
            'student_id' => $studentId,
            'score' => $score,
            'feedback' => sprintf('Auto-graded: %d/%d', $score, $maxScore),
        ]);

        backend_db()->prepare('UPDATE exam_assignments SET status = :status WHERE id = :id')
            ->execute(['status' => 'submitted', 'id' => $assignmentId]);

        return [
            'score' => $score,
            'max_score' => $maxScore,
        ];
    }
}
