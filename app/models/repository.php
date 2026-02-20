<?php

require_once __DIR__ . '/../helpers.php';

function find_user_by_email(string $email): ?array
{
    $stmt = db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    return $stmt->fetch() ?: null;
}

function get_all(string $table): array
{
    return db()->query("SELECT * FROM {$table} ORDER BY id DESC")->fetchAll();
}

function create_entity(string $table, array $data): bool
{
    $columns = implode(',', array_keys($data));
    $placeholders = ':' . implode(',:', array_keys($data));
    $stmt = db()->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
    return $stmt->execute($data);
}

function update_entity(string $table, int $id, array $data): bool
{
    $set = implode(',', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));
    $data['id'] = $id;
    $stmt = db()->prepare("UPDATE {$table} SET {$set} WHERE id = :id");
    return $stmt->execute($data);
}

function delete_entity(string $table, int $id): bool
{
    $stmt = db()->prepare("DELETE FROM {$table} WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function find_by_id(string $table, int $id): ?array
{
    $stmt = db()->prepare("SELECT * FROM {$table} WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch() ?: null;
}

function teacher_exams(int $teacherId): array
{
    $stmt = db()->prepare('SELECT * FROM exams WHERE teacher_id = :teacher_id ORDER BY id DESC');
    $stmt->execute(['teacher_id' => $teacherId]);
    return $stmt->fetchAll();
}

function student_assigned_exams(int $studentId): array
{
    $sql = 'SELECT a.id as assignment_id, e.*, a.schedule_at, a.status as assignment_status
            FROM exam_assignments a
            JOIN exams e ON e.id = a.exam_id
            WHERE a.student_id = :student_id
            ORDER BY a.schedule_at ASC';
    $stmt = db()->prepare($sql);
    $stmt->execute(['student_id' => $studentId]);
    return $stmt->fetchAll();
}

function questions_for_exam(int $examId): array
{
    $stmt = db()->prepare('SELECT * FROM questions WHERE exam_id = :exam_id ORDER BY id ASC');
    $stmt->execute(['exam_id' => $examId]);
    return $stmt->fetchAll();
}

function record_submission(int $assignmentId, int $studentId, int $score, string $feedback): bool
{
    $stmt = db()->prepare('INSERT INTO results (assignment_id, student_id, score, feedback, submitted_at) VALUES (:assignment_id, :student_id, :score, :feedback, NOW())');
    $ok = $stmt->execute([
        'assignment_id' => $assignmentId,
        'student_id' => $studentId,
        'score' => $score,
        'feedback' => $feedback,
    ]);

    if ($ok) {
        db()->prepare('UPDATE exam_assignments SET status = "submitted" WHERE id = :id')->execute(['id' => $assignmentId]);
    }

    return $ok;
}

function result_for_assignment(int $assignmentId): ?array
{
    $stmt = db()->prepare('SELECT * FROM results WHERE assignment_id = :assignment_id LIMIT 1');
    $stmt->execute(['assignment_id' => $assignmentId]);
    return $stmt->fetch() ?: null;
}

function performance_report(): array
{
    $sql = 'SELECT s.full_name AS student_name, e.title AS exam_title, r.score, e.total_marks, r.submitted_at
            FROM results r
            JOIN users s ON s.id = r.student_id
            JOIN exam_assignments a ON a.id = r.assignment_id
            JOIN exams e ON e.id = a.exam_id
            ORDER BY r.submitted_at DESC';
    return db()->query($sql)->fetchAll();
}

function monitoring_logs(): array
{
    return db()->query('SELECT m.*, u.full_name FROM monitoring_logs m JOIN users u ON u.id = m.user_id ORDER BY m.id DESC LIMIT 100')->fetchAll();
}

function add_monitor_log(int $userId, string $eventType, string $details): bool
{
    $stmt = db()->prepare('INSERT INTO monitoring_logs (user_id, event_type, details, created_at) VALUES (:user_id, :event_type, :details, NOW())');
    return $stmt->execute([
        'user_id' => $userId,
        'event_type' => $eventType,
        'details' => $details,
    ]);
}
