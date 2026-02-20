<?php
session_start();

require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/models/repository.php';

$page = $_GET['page'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'];

if ($page === 'register' && $method === 'POST') {
    $data = [
        'full_name' => post('full_name'),
        'email' => post('email'),
        'password' => password_hash(post('password'), PASSWORD_BCRYPT),
        'role' => 'student',
    ];
    create_entity('users', $data);
    flash('Registration successful. Please login.');
    redirect('login');
}

if ($page === 'login' && $method === 'POST') {
    $user = find_user_by_email(post('email'));
    if ($user && password_verify(post('password'), $user['password'])) {
        $_SESSION['user'] = $user;
        add_monitor_log((int)$user['id'], 'login', 'User logged in');
        redirect($user['role'] . '-dashboard');
    }
    flash('Invalid credentials');
    redirect('login');
}

if ($page === 'logout') {
    if (session_user()) {
        add_monitor_log((int)session_user()['id'], 'logout', 'User logged out');
    }
    session_destroy();
    redirect('login');
}

if ($method === 'POST' && str_starts_with($page, 'admin-')) {
    require_auth('admin');
    $action = str_replace('admin-', '', $page);
    if ($action === 'save-student' || $action === 'save-teacher') {
        $id = (int)post('id', 0);
        $entityRole = $action === 'save-student' ? 'student' : 'teacher';
        $payload = [
            'full_name' => post('full_name'),
            'email' => post('email'),
            'role' => $entityRole,
        ];
        if (post('password')) {
            $payload['password'] = password_hash(post('password'), PASSWORD_BCRYPT);
        }

        if ($id > 0) {
            if (!isset($payload['password'])) {
                unset($payload['password']);
            }
            update_entity('users', $id, $payload);
        } else {
            $payload['password'] = $payload['password'] ?? password_hash('password123', PASSWORD_BCRYPT);
            create_entity('users', $payload);
        }
    }

    if ($action === 'delete-user') {
        delete_entity('users', (int)post('id'));
    }

    if ($action === 'save-exam') {
        $id = (int)post('id', 0);
        $payload = [
            'title' => post('title'),
            'subject' => post('subject'),
            'topic' => post('topic'),
            'duration_minutes' => (int)post('duration_minutes'),
            'total_marks' => (int)post('total_marks'),
            'teacher_id' => (int)post('teacher_id'),
        ];
        $id > 0 ? update_entity('exams', $id, $payload) : create_entity('exams', $payload);
    }

    if ($action === 'assign-exam') {
        create_entity('exam_assignments', [
            'exam_id' => (int)post('exam_id'),
            'student_id' => (int)post('student_id'),
            'schedule_at' => post('schedule_at'),
            'status' => 'assigned',
        ]);
    }

    redirect('admin-dashboard');
}

if ($method === 'POST' && str_starts_with($page, 'teacher-')) {
    require_auth('teacher');
    $action = str_replace('teacher-', '', $page);

    if ($action === 'save-question') {
        create_entity('questions', [
            'exam_id' => (int)post('exam_id'),
            'question_text' => post('question_text'),
            'question_type' => post('question_type'),
            'option_a' => post('option_a'),
            'option_b' => post('option_b'),
            'option_c' => post('option_c'),
            'option_d' => post('option_d'),
            'correct_answer' => post('correct_answer'),
            'marks' => (int)post('marks'),
        ]);
    }

    if ($action === 'save-exam') {
        create_entity('exams', [
            'title' => post('title'),
            'subject' => post('subject'),
            'topic' => post('topic'),
            'duration_minutes' => (int)post('duration_minutes'),
            'total_marks' => (int)post('total_marks'),
            'teacher_id' => (int)session_user()['id'],
        ]);
    }

    redirect('teacher-dashboard');
}

if ($method === 'POST' && str_starts_with($page, 'student-')) {
    require_auth('student');
    $action = str_replace('student-', '', $page);

    if ($action === 'submit-exam') {
        $assignmentId = (int)post('assignment_id');
        $questions = questions_for_exam((int)post('exam_id'));
        $score = 0;
        foreach ($questions as $q) {
            $answer = post('answer_' . $q['id']);
            if ($answer === $q['correct_answer']) {
                $score += (int)$q['marks'];
            }
        }
        record_submission($assignmentId, (int)session_user()['id'], $score, 'Auto-graded submission completed.');
        flash('Exam submitted successfully.');
    }

    if ($action === 'monitor-event') {
        add_monitor_log((int)session_user()['id'], post('event_type', 'unknown'), post('details', ''));
        header('Content-Type: application/json');
        echo json_encode(['ok' => true]);
        exit;
    }

    redirect('student-dashboard');
}

switch ($page) {
    case 'home':
        render('home');
        break;
    case 'login':
        render('auth/login');
        break;
    case 'register':
        render('auth/register');
        break;
    case 'admin-dashboard':
        require_auth('admin');
        render('admin/dashboard', [
            'students' => db()->query("SELECT * FROM users WHERE role='student'")->fetchAll(),
            'teachers' => db()->query("SELECT * FROM users WHERE role='teacher'")->fetchAll(),
            'exams' => get_all('exams'),
            'assignments' => get_all('exam_assignments'),
            'reports' => performance_report(),
            'logs' => monitoring_logs(),
        ]);
        break;
    case 'teacher-dashboard':
        require_auth('teacher');
        render('teacher/dashboard', [
            'exams' => teacher_exams((int)session_user()['id']),
            'performance' => performance_report(),
        ]);
        break;
    case 'student-dashboard':
        require_auth('student');
        render('student/dashboard', [
            'assignments' => student_assigned_exams((int)session_user()['id']),
        ]);
        break;
    case 'student-attempt':
        require_auth('student');
        $assignment = find_by_id('exam_assignments', (int)($_GET['assignment_id'] ?? 0));
        $exam = $assignment ? find_by_id('exams', (int)$assignment['exam_id']) : null;
        render('student/attempt', [
            'assignment' => $assignment,
            'exam' => $exam,
            'questions' => $exam ? questions_for_exam((int)$exam['id']) : [],
        ]);
        break;
    default:
        http_response_code(404);
        echo 'Page not found';
}
