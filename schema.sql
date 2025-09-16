-- Attendance System Database Schema
-- Run this script to create the database and tables

CREATE DATABASE IF NOT EXISTS attendance_system;
USE attendance_system;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    date DATE NOT NULL,
    status ENUM('present', 'absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, date)
);

-- Insert test data
INSERT INTO students (name, email, phone) VALUES
('John Doe', 'john.doe@email.com', '555-0101'),
('Jane Smith', 'jane.smith@email.com', '555-0102'),
('Mike Johnson', 'mike.johnson@email.com', '555-0103'),
('Sarah Wilson', 'sarah.wilson@email.com', '555-0104'),
('David Brown', 'david.brown@email.com', '555-0105'),
('Emily Davis', 'emily.davis@email.com', '555-0106'),
('Robert Taylor', 'robert.taylor@email.com', '555-0107'),
('Lisa Anderson', 'lisa.anderson@email.com', '555-0108'),
('James Wilson', 'james.wilson@email.com', '555-0109'),
('Maria Garcia', 'maria.garcia@email.com', '555-0110'),
('Christopher Lee', 'chris.lee@email.com', '555-0111'),
('Amanda White', 'amanda.white@email.com', '555-0112'),
('Daniel Harris', 'daniel.harris@email.com', '555-0113'),
('Jennifer Clark', 'jennifer.clark@email.com', '555-0114'),
('Matthew Martinez', 'matthew.martinez@email.com', '555-0115'),
('Ashley Thompson', 'ashley.thompson@email.com', '555-0116'),
('Joshua Rodriguez', 'joshua.rodriguez@email.com', '555-0117'),
('Nicole Lewis', 'nicole.lewis@email.com', '555-0118'),
('Andrew Walker', 'andrew.walker@email.com', '555-0119'),
('Stephanie Hall', 'stephanie.hall@email.com', '555-0120');

-- Insert comprehensive attendance data for the last 30 days
INSERT INTO attendance (student_id, date, status) VALUES
-- September 2025 attendance (current month)
(1, '2025-09-01', 'present'), (2, '2025-09-01', 'present'), (3, '2025-09-01', 'absent'), (4, '2025-09-01', 'present'), (5, '2025-09-01', 'present'),
(6, '2025-09-01', 'present'), (7, '2025-09-01', 'absent'), (8, '2025-09-01', 'present'), (9, '2025-09-01', 'present'), (10, '2025-09-01', 'present'),
(11, '2025-09-01', 'present'), (12, '2025-09-01', 'present'), (13, '2025-09-01', 'absent'), (14, '2025-09-01', 'present'), (15, '2025-09-01', 'present'),
(16, '2025-09-01', 'present'), (17, '2025-09-01', 'present'), (18, '2025-09-01', 'present'), (19, '2025-09-01', 'absent'), (20, '2025-09-01', 'present'),

(1, '2025-09-02', 'present'), (2, '2025-09-02', 'absent'), (3, '2025-09-02', 'present'), (4, '2025-09-02', 'present'), (5, '2025-09-02', 'present'),
(6, '2025-09-02', 'present'), (7, '2025-09-02', 'present'), (8, '2025-09-02', 'present'), (9, '2025-09-02', 'absent'), (10, '2025-09-02', 'present'),
(11, '2025-09-02', 'present'), (12, '2025-09-02', 'absent'), (13, '2025-09-02', 'present'), (14, '2025-09-02', 'present'), (15, '2025-09-02', 'present'),
(16, '2025-09-02', 'present'), (17, '2025-09-02', 'present'), (18, '2025-09-02', 'absent'), (19, '2025-09-02', 'present'), (20, '2025-09-02', 'present'),

(1, '2025-09-03', 'present'), (2, '2025-09-03', 'present'), (3, '2025-09-03', 'present'), (4, '2025-09-03', 'absent'), (5, '2025-09-03', 'present'),
(6, '2025-09-03', 'absent'), (7, '2025-09-03', 'present'), (8, '2025-09-03', 'present'), (9, '2025-09-03', 'present'), (10, '2025-09-03', 'present'),
(11, '2025-09-03', 'present'), (12, '2025-09-03', 'present'), (13, '2025-09-03', 'present'), (14, '2025-09-03', 'absent'), (15, '2025-09-03', 'present'),
(16, '2025-09-03', 'present'), (17, '2025-09-03', 'absent'), (18, '2025-09-03', 'present'), (19, '2025-09-03', 'present'), (20, '2025-09-03', 'present'),

(1, '2025-09-04', 'absent'), (2, '2025-09-04', 'present'), (3, '2025-09-04', 'present'), (4, '2025-09-04', 'present'), (5, '2025-09-04', 'present'),
(6, '2025-09-04', 'present'), (7, '2025-09-04', 'present'), (8, '2025-09-04', 'absent'), (9, '2025-09-04', 'present'), (10, '2025-09-04', 'present'),
(11, '2025-09-04', 'absent'), (12, '2025-09-04', 'present'), (13, '2025-09-04', 'present'), (14, '2025-09-04', 'present'), (15, '2025-09-04', 'present'),
(16, '2025-09-04', 'present'), (17, '2025-09-04', 'present'), (18, '2025-09-04', 'present'), (19, '2025-09-04', 'present'), (20, '2025-09-04', 'absent'),

(1, '2025-09-05', 'present'), (2, '2025-09-05', 'present'), (3, '2025-09-05', 'absent'), (4, '2025-09-05', 'present'), (5, '2025-09-05', 'present'),
(6, '2025-09-05', 'present'), (7, '2025-09-05', 'absent'), (8, '2025-09-05', 'present'), (9, '2025-09-05', 'present'), (10, '2025-09-05', 'absent'),
(11, '2025-09-05', 'present'), (12, '2025-09-05', 'present'), (13, '2025-09-05', 'present'), (14, '2025-09-05', 'present'), (15, '2025-09-05', 'present'),
(16, '2025-09-05', 'absent'), (17, '2025-09-05', 'present'), (18, '2025-09-05', 'present'), (19, '2025-09-05', 'present'), (20, '2025-09-05', 'present'),

-- Continue with more recent dates
(1, '2025-09-10', 'present'), (2, '2025-09-10', 'present'), (3, '2025-09-10', 'present'), (4, '2025-09-10', 'present'), (5, '2025-09-10', 'absent'),
(6, '2025-09-10', 'present'), (7, '2025-09-10', 'present'), (8, '2025-09-10', 'present'), (9, '2025-09-10', 'present'), (10, '2025-09-10', 'present'),
(11, '2025-09-10', 'present'), (12, '2025-09-10', 'absent'), (13, '2025-09-10', 'present'), (14, '2025-09-10', 'present'), (15, '2025-09-10', 'present'),
(16, '2025-09-10', 'present'), (17, '2025-09-10', 'present'), (18, '2025-09-10', 'present'), (19, '2025-09-10', 'absent'), (20, '2025-09-10', 'present'),

(1, '2025-09-11', 'present'), (2, '2025-09-11', 'absent'), (3, '2025-09-11', 'present'), (4, '2025-09-11', 'present'), (5, '2025-09-11', 'present'),
(6, '2025-09-11', 'present'), (7, '2025-09-11', 'present'), (8, '2025-09-11', 'absent'), (9, '2025-09-11', 'present'), (10, '2025-09-11', 'present'),
(11, '2025-09-11', 'present'), (12, '2025-09-11', 'present'), (13, '2025-09-11', 'present'), (14, '2025-09-11', 'absent'), (15, '2025-09-11', 'present'),
(16, '2025-09-11', 'present'), (17, '2025-09-11', 'present'), (18, '2025-09-11', 'present'), (19, '2025-09-11', 'present'), (20, '2025-09-11', 'present'),

(1, '2025-09-12', 'present'), (2, '2025-09-12', 'present'), (3, '2025-09-12', 'absent'), (4, '2025-09-12', 'present'), (5, '2025-09-12', 'present'),
(6, '2025-09-12', 'absent'), (7, '2025-09-12', 'present'), (8, '2025-09-12', 'present'), (9, '2025-09-12', 'present'), (10, '2025-09-12', 'present'),
(11, '2025-09-12', 'present'), (12, '2025-09-12', 'present'), (13, '2025-09-12', 'present'), (14, '2025-09-12', 'present'), (15, '2025-09-12', 'absent'),
(16, '2025-09-12', 'present'), (17, '2025-09-12', 'absent'), (18, '2025-09-12', 'present'), (19, '2025-09-12', 'present'), (20, '2025-09-12', 'present'),

(1, '2025-09-13', 'present'), (2, '2025-09-13', 'present'), (3, '2025-09-13', 'present'), (4, '2025-09-13', 'absent'), (5, '2025-09-13', 'present'),
(6, '2025-09-13', 'present'), (7, '2025-09-13', 'present'), (8, '2025-09-13', 'present'), (9, '2025-09-13', 'absent'), (10, '2025-09-13', 'present'),
(11, '2025-09-13', 'present'), (12, '2025-09-13', 'present'), (13, '2025-09-13', 'absent'), (14, '2025-09-13', 'present'), (15, '2025-09-13', 'present'),
(16, '2025-09-13', 'present'), (17, '2025-09-13', 'present'), (18, '2025-09-13', 'present'), (19, '2025-09-13', 'present'), (20, '2025-09-13', 'present'),

(1, '2025-09-14', 'present'), (2, '2025-09-14', 'absent'), (3, '2025-09-14', 'present'), (4, '2025-09-14', 'present'), (5, '2025-09-14', 'absent'),
(6, '2025-09-14', 'present'), (7, '2025-09-14', 'present'), (8, '2025-09-14', 'present'), (9, '2025-09-14', 'present'), (10, '2025-09-14', 'present'),
(11, '2025-09-14', 'present'), (12, '2025-09-14', 'present'), (13, '2025-09-14', 'present'), (14, '2025-09-14', 'present'), (15, '2025-09-14', 'present'),
(16, '2025-09-14', 'absent'), (17, '2025-09-14', 'present'), (18, '2025-09-14', 'present'), (19, '2025-09-14', 'present'), (20, '2025-09-14', 'present'),

(1, '2025-09-15', 'present'), (2, '2025-09-15', 'present'), (3, '2025-09-15', 'absent'), (4, '2025-09-15', 'present'), (5, '2025-09-15', 'present'),
(6, '2025-09-15', 'present'), (7, '2025-09-15', 'present'), (8, '2025-09-15', 'present'), (9, '2025-09-15', 'present'), (10, '2025-09-15', 'absent'),
(11, '2025-09-15', 'present'), (12, '2025-09-15', 'present'), (13, '2025-09-15', 'present'), (14, '2025-09-15', 'present'), (15, '2025-09-15', 'present'),
(16, '2025-09-15', 'present'), (17, '2025-09-15', 'present'), (18, '2025-09-15', 'absent'), (19, '2025-09-15', 'present'), (20, '2025-09-15', 'present'),

-- Add some August 2025 data for monthly reports
(1, '2025-08-28', 'present'), (2, '2025-08-28', 'present'), (3, '2025-08-28', 'present'), (4, '2025-08-28', 'absent'), (5, '2025-08-28', 'present'),
(6, '2025-08-28', 'present'), (7, '2025-08-28', 'present'), (8, '2025-08-28', 'present'), (9, '2025-08-28', 'present'), (10, '2025-08-28', 'present'),

(1, '2025-08-29', 'present'), (2, '2025-08-29', 'absent'), (3, '2025-08-29', 'present'), (4, '2025-08-29', 'present'), (5, '2025-08-29', 'present'),
(6, '2025-08-29', 'present'), (7, '2025-08-29', 'present'), (8, '2025-08-29', 'absent'), (9, '2025-08-29', 'present'), (10, '2025-08-29', 'present'),

(1, '2025-08-30', 'absent'), (2, '2025-08-30', 'present'), (3, '2025-08-30', 'present'), (4, '2025-08-30', 'present'), (5, '2025-08-30', 'present'),
(6, '2025-08-30', 'present'), (7, '2025-08-30', 'present'), (8, '2025-08-30', 'present'), (9, '2025-08-30', 'present'), (10, '2025-08-30', 'absent');