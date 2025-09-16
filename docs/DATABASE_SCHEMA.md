# DATABASE SCHEMA DOCUMENTATION

## TỔNG QUAN

### **Database Information**
- **Engine**: MySQL 8.0
- **Character Set**: utf8mb4
- **Collation**: utf8mb4_unicode_ci
- **Time Zone**: UTC
- **Database Name**: `attendance_system`

---

## ENTITY RELATIONSHIP DIAGRAM

```
┌─────────────────┐         ┌─────────────────┐
│    students     │         │   attendance    │
├─────────────────┤         ├─────────────────┤
│ student_id (PK) │         │ id (PK)         │
│ name            │◄────────┤ student_id (FK) │
│ email (UNIQUE)  │         │ date            │
│ phone           │         │ status          │
│ created_at      │         │ created_at      │
└─────────────────┘         └─────────────────┘
```

**Relationship**: One-to-Many (1 student → N attendance records)

---

## TABLE STRUCTURES

### **1. students**
Lưu trữ thông tin học sinh

```sql
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### **Columns Description**
| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| `id` | int | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `name` | varchar(100) | NO | | Tên đầy đủ của học sinh |
| `email` | varchar(100) | NO | | Email học sinh (unique) |
| `phone` | varchar(20) | YES | NULL | Số điện thoại (optional) |
| `created_at` | timestamp | YES | CURRENT_TIMESTAMP | Thời gian tạo record |

#### **Indexes**
- **PRIMARY**: `id` (Clustered index)
- **UNIQUE**: `email` (Đảm bảo email duy nhất)
- **INDEX**: `idx_name` (Tìm kiếm theo tên)
- **INDEX**: `idx_created_at` (Sắp xếp theo ngày tạo)

#### **Constraints**
- `name`: NOT NULL, max 100 characters
- `email`: NOT NULL, UNIQUE, max 100 characters, valid email format
- `phone`: Optional, max 20 characters

---

### **2. attendance**
Lưu trữ dữ liệu điểm danh hàng ngày

```sql
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    date DATE NOT NULL,
    status ENUM('present', 'absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    UNIQUE KEY unique_attendance (student_id, date)
);
```

#### **Columns Description**
| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| `id` | int | NO | AUTO_INCREMENT | Primary key, unique identifier |
| `student_id` | int | YES | NULL | Foreign key reference to students.id |
| `date` | date | NO | | Ngày điểm danh (YYYY-MM-DD) |
| `status` | enum | NO | | Trạng thái: 'present' hoặc 'absent' |
| `created_at` | timestamp | YES | CURRENT_TIMESTAMP | Thời gian tạo record |

#### **Indexes**
- **PRIMARY**: `id` (Clustered index)
- **UNIQUE**: `unique_student_date` (Đảm bảo 1 học sinh chỉ có 1 record/ngày)
- **INDEX**: `idx_date` (Truy vấn theo ngày)
- **INDEX**: `idx_status` (Thống kê theo trạng thái)
- **INDEX**: `idx_created_at` (Sắp xếp theo thời gian)

#### **Foreign Keys**
- `student_id` → `students.id` (CASCADE DELETE)

#### **Constraints**
- `student_id`: Foreign key, optional (có thể NULL nếu học sinh bị xóa)
- `date`: NOT NULL, valid date format
- `status`: ENUM('present', 'absent'), NOT NULL
- **UNIQUE**: (student_id, date) - Mỗi học sinh chỉ có 1 record điểm danh/ngày

---

## 📋 SAMPLE DATA

### **Students Sample Data**
```sql
INSERT INTO `attendance_system`.students (name, email, phone) VALUES
('Oliver Thompson', 'oliver.thompson@example.com', '0911122233'),
('Sophia Martinez', 'sophia.martinez@example.com', '0912233445'),
('James Anderson', 'james.anderson@example.com', '0913344556'),
('Isabella Clark', 'isabella.clark@example.com', '0914455667'),
('Benjamin Lewis', 'benjamin.lewis@example.com', '0915566778');

```
### **Check students sample data id**

```sql
SELECT id, name FROM `attendance_system`.students ORDER BY id;
```

### **Attendance Sample Data**
```sql
INSERT INTO `attendance_system`.attendance (student_id, date, status) VALUES
(<match_id_above>, '2025-09-15', 'present'),
(<match_id_above>, '2025-09-15', 'absent'),
(<match_id_above>, '2025-09-15', 'present'),
(<match_id_above>, '2025-09-15', 'present'),
(<match_id_above>, '2025-09-15', 'absent'),
(<match_id_above>, '2025-09-14', 'absent'),
(<match_id_above>, '2025-09-14', 'present'),
(<match_id_above>, '2025-09-14', 'present'),
(<match_id_above>, '2025-09-14', 'absent'),
(<match_id_above>, '2025-09-14', 'present');
```

---

## 🔍 COMMON QUERIES

### **1. Lấy tất cả học sinh**
```sql
SELECT id, name, email, phone, created_at 
FROM `attendance_system`.students 
ORDER BY name ASC;
```

### **2. Lấy điểm danh theo ngày**
```sql
SELECT 
    s.id,
    s.name,
    s.email,
    COALESCE(a.status, 'not_marked') as status
FROM `attendance_system`.students s
LEFT JOIN `attendance_system`.attendance a ON s.id = a.student_id AND a.date = '2025-09-15'
ORDER BY s.name;
```

### **3. Thống kê điểm danh theo ngày**
```sql
SELECT 
    date,
    COUNT(*) as total_records,
    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count,
    ROUND(
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 
        2
    ) as attendance_rate
FROM `attendance_system`.attendance 
WHERE date BETWEEN '2025-09-01' AND '2025-09-30'
GROUP BY date
ORDER BY date DESC;
```

### **4. Tỷ lệ điểm danh của từng học sinh**
```sql
SELECT 
    s.id,
    s.name,
    s.email,
    COUNT(a.id) as total_days,
    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_days,
    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_days,
    ROUND(
        SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) * 100.0 / COUNT(a.id), 
        2
    ) as attendance_rate
FROM `attendance_system`.students s
LEFT JOIN `attendance_system`.attendance a ON s.id = a.student_id
WHERE a.date BETWEEN '2025-09-01' AND '2025-09-30'
GROUP BY s.id, s.name, s.email
ORDER BY attendance_rate DESC;
```

### **5. Học sinh vắng mặt nhiều nhất**
```sql
SELECT 
    s.name,
    s.email,
    COUNT(a.id) as absent_days
FROM `attendance_system`.students s
INNER JOIN `attendance_system`.attendance a ON s.id = a.student_id
WHERE a.status = 'absent' 
    AND a.date BETWEEN '2025-09-01' AND '2025-09-30'
GROUP BY s.id, s.name, s.email
ORDER BY absent_days DESC
LIMIT 10;
```

### **6. Báo cáo tháng**
```sql
SELECT 
    DATE_FORMAT(a.date, '%Y-%m') as month,
    COUNT(DISTINCT a.student_id) as active_students,
    COUNT(a.id) as total_records,
    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
    ROUND(
        SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) * 100.0 / COUNT(a.id), 
        2
    ) as monthly_rate
FROM `attendance_system`.attendance a
GROUP BY DATE_FORMAT(a.date, '%Y-%m')
ORDER BY month DESC;
```
*Last Updated: 15/09/2025*  
