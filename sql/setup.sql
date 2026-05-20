CREATE DATABASE IF NOT EXISTS attendance_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE attendance_app;

CREATE TABLE IF NOT EXISTS attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_name VARCHAR(150) NOT NULL,
  roll_number VARCHAR(50) NOT NULL,
  attendance_date DATE NOT NULL,
  status ENUM('Present', 'Absent') NOT NULL DEFAULT 'Present',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO attendance (student_name, roll_number, attendance_date, status) VALUES
  ('Ayesha Rahman',   '101', CURDATE(), 'Present'),
  ('Tanvir Hossain',  '102', CURDATE(), 'Absent'),
  ('Nusrat Jahan',    '103', CURDATE(), 'Present');
