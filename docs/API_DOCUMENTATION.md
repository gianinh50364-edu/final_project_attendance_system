# 🔧 API DOCUMENTATION

##  OVERVIEW

The Attendance Management System API provides RESTful endpoints for managing students and attendance. All APIs return JSON data and use standard HTTP status codes.

### **Base URL**
```
Development: http://localhost:8080/api/
```

### **Authentication**
Currently the API does not require authentication, but will be implemented in future versions.

### **Response Format**
```json
{
  "success": true|false,
  "message": "Description of the result",
  "data": { ... },
  "error": "Error details (if any)"
}
```

---

##  STUDENT MANAGEMENT API

### **GET /api/get_students.php**
Get list of all students

#### **Request**
```http
GET /api/get_students.php
```

#### **Response**
```json
{
  "success": true,
  "message": "Students retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Nguyen Van A",
      "email": "nguyenvana@example.com",
      "phone": "0901234567",
      "created_at": "2025-09-15 10:30:00"
    },
    {
      "id": 2,
      "name": "Tran Thi B",
      "email": "tranthib@example.com", 
      "phone": "0907654321",
      "created_at": "2025-09-15 11:15:00"
    }
  ]
}
```

### **POST /api/add_student.php**
Add new student

#### **Request**
```http
POST /api/add_student.php
Content-Type: application/json

{
  "name": "Le Van C",
  "email": "levanc@example.com",
  "phone": "0909876543"
}
```

#### **Response**
```json
{
  "success": true,
  "message": "Student added successfully",
  "data": {
    "id": 3,
    "name": "Le Van C",
    "email": "levanc@example.com",
    "phone": "0909876543"
  }
}
```

#### **Validation Rules**
- `name`: Required, max 100 characters
- `email`: Required, valid email format, unique
- `phone`: Optional, 10-11 digits

### **DELETE /api/delete_student.php**
Delete student

#### **Request**
```http
DELETE /api/delete_student.php
Content-Type: application/json

{
  "id": 3
}
```

#### **Response**
```json
{
  "success": true,
  "message": "Student deleted successfully"
}
```

---

##  ATTENDANCE API

### **POST /api/mark_attendance.php**
Mark attendance for student

#### **Request**
```http
POST /api/mark_attendance.php
Content-Type: application/json

{
  "student_id": 1,
  "status": "present",
  "date": "2025-09-15"
}
```

#### **Parameters**
- `student_id`: Student ID (required)
- `status`: "present" or "absent" (required)
- `date`: Attendance date, format YYYY-MM-DD (optional, default: today)

#### **Response**
```json
{
  "success": true,
  "message": "Attendance recorded successfully",
  "action": "created",
  "data": {
    "id": 15,
    "student_id": 1,
    "date": "2025-09-15",
    "status": "present"
  }
}
```

### **GET /api/get_attendance.php**
Get attendance data

#### **Get Attendance by Date**
```http
GET /api/get_attendance.php?action=by_date&date=2025-09-15
```

#### **Response**
```json
{
  "status": "success",
  "data": {
    "date": "2025-09-15",
    "attendance": [
      {
        "id": 1,
        "name": "Nguyen Van A",
        "email": "nguyenvana@example.com",
        "status": "present"
      },
      {
        "id": 2,
        "name": "Tran Thi B", 
        "email": "tranthib@example.com",
        "status": "absent"
      }
    ],
    "summary": {
      "total": 20,
      "present": 18,
      "absent": 2,
      "percentage": 90.0
    }
  }
}
```

### **GET /api/get_student_stats.php**
Get student attendance statistics

#### **Request**
```http
GET /api/get_student_stats.php?student_id=1&from_date=2025-09-01&to_date=2025-09-30
```

#### **Response**
```json
{
  "success": true,
  "data": {
    "student_info": {
      "id": 1,
      "name": "Nguyen Van A",
      "email": "nguyenvana@example.com"
    },
    "stats": {
      "total_days": 20,
      "present_days": 18,
      "absent_days": 2,
      "attendance_rate": 90.0
    },
    "attendance_history": [
      {
        "date": "2025-09-15",
        "status": "present"
      },
      {
        "date": "2025-09-14",
        "status": "present"
      }
    ]
  }
}
```

---

##  REPORTS API

### **GET /api/monthly_report.php**
Generate monthly attendance report

#### **Request**
```http
GET /api/monthly_report.php?month=2025-09
```

#### **Response**
```json
{
  "success": true,
  "data": {
    "month": "2025-09",
    "total_students": 20,
    "total_school_days": 22,
    "overall_stats": {
      "total_attendance_records": 440,
      "present_count": 396,
      "absent_count": 44,
      "overall_rate": 90.0
    },
    "daily_stats": [
      {
        "date": "2025-09-01",
        "present": 19,
        "absent": 1,
        "rate": 95.0
      },
      {
        "date": "2025-09-02", 
        "present": 18,
        "absent": 2,
        "rate": 90.0
      }
    ],
    "student_summary": [
      {
        "student_id": 1,
        "name": "Nguyen Van A",
        "present_days": 20,
        "absent_days": 2,
        "rate": 90.9
      }
    ]
  }
}
```

---

## ❌ ERROR HANDLING

### **HTTP Status Codes**
- `200`: Success
- `400`: Bad Request (validation errors)
- `404`: Not Found
- `405`: Method Not Allowed
- `500`: Internal Server Error

### **Error Response Format**
```json
{
  "success": false,
  "message": "Error description",
  "error": {
    "code": "VALIDATION_ERROR",
    "details": {
      "name": ["Name is required"],
      "email": ["Email format is invalid"]
    }
  }
}
```

### **Common Error Codes**
- `VALIDATION_ERROR`: Input validation failed
- `NOT_FOUND`: Resource not found
- `DUPLICATE_ENTRY`: Unique constraint violation
- `DATABASE_ERROR`: Database operation failed
- `INVALID_METHOD`: HTTP method not allowed

---

## 🧪 TESTING EXAMPLES

### **cURL Examples**

#### **Add Student**
```bash
curl -X POST http://localhost:8080/api/add_student.php \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Student",
    "email": "test@example.com",
    "phone": "0901234567"
  }'
```

#### **Mark Attendance**
```bash
curl -X POST http://localhost:8080/api/mark_attendance.php \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "status": "present",
    "date": "2025-09-15"
  }'
```

#### **Get Students**
```bash
curl -X GET http://localhost:8080/api/get_students.php
```

---

## 🔧 BRUNO API TESTING

Bruno is a modern API testing tool that provides a Git-friendly approach to API testing. Our project includes a complete Bruno collection with all API endpoints.

*See more at `TESTING_GUIDELINES.md`*

*API Documentation Updated: 15/09/2025*
