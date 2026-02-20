CREATE DATABASE IF NOT EXISTS online_exam_system;
USE online_exam_system;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','teacher','student') NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE exams (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  subject VARCHAR(100) NOT NULL,
  topic VARCHAR(120) NOT NULL,
  duration_minutes INT NOT NULL,
  total_marks INT NOT NULL,
  teacher_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE questions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  question_text TEXT NOT NULL,
  question_type ENUM('MCQ','True/False') NOT NULL,
  option_a VARCHAR(255),
  option_b VARCHAR(255),
  option_c VARCHAR(255),
  option_d VARCHAR(255),
  correct_answer VARCHAR(255) NOT NULL,
  marks INT NOT NULL DEFAULT 1,
  FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE
);

CREATE TABLE exam_assignments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  exam_id INT NOT NULL,
  student_id INT NOT NULL,
  schedule_at DATETIME NOT NULL,
  status ENUM('assigned','in_progress','submitted') DEFAULT 'assigned',
  FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  assignment_id INT NOT NULL,
  student_id INT NOT NULL,
  score INT NOT NULL,
  feedback TEXT,
  submitted_at DATETIME NOT NULL,
  FOREIGN KEY (assignment_id) REFERENCES exam_assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE monitoring_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  event_type VARCHAR(80) NOT NULL,
  details TEXT,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (full_name, email, password, role) VALUES
('System Admin', 'admin@exam.com', '$2y$10$0bhPtQkPTN7hWZV2BdrQce1EaBr8r9BU2NBPqe10S3Vb7NUSfuwWy', 'admin');
-- default password: admin123
