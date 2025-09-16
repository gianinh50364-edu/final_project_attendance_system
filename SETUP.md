# Attendance System - Quick Setup Guide

## 🚀 Quick Installation

### Prerequisites
- Docker installed on your system
- Docker Compose installed
- At least 2GB free disk space
- Ports 8080, 8081, and 3306 available
### Option 1: Docker Deployment (Recommended)
```bash
# Clone/Download the project
cd attendance_sys

# Start with Docker Compose
docker-compose up -d

# Access the application
# Main App: http://localhost:8080
# phpMyAdmin: http://localhost:8081
```

### Option 2: Automated Deployment Scripts
```bash
# For Linux/macOS
./deploy.sh

# For Windows
deploy.bat
```
*Note: These scripts will automatically set up Docker containers and start the services*


## 🎯 Default Credentials & Access

### Database Access
- **Host**: localhost:3306 (or database for Docker)
- **Database**: attendance_system
- **Username**: attendance_user
- **Password**: attendance_pass

### Application Access
- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

## 📊 Default Sample Data
The system comes with sample students and attendance records for testing:
- John Doe
- Jane Smith  
- Mike Johnson
- Sarah Wilson
- David Brown

## Features Implemented

### ✅ Core Features
- **Student Management**: Add, view, delete students
- **Attendance Tracking**: Mark students present/absent by date
- **Dashboard**: Today's attendance summary and statistics
- **Reports**: Student statistics and monthly reports
- **Responsive Design**: Works on desktop, tablet, and mobile

### ✅ Technical Features
- **RESTful API**: JSON-based endpoints for all operations
- **AJAX Interface**: No page reloads for most operations
- **Form Validation**: Client-side and server-side validation
- **Error Handling**: Comprehensive error messages
- **Security**: SQL injection prevention, input sanitization

### ✅ User Interface
- **Bootstrap 5**: Modern, responsive design
- **Interactive Elements**: Smooth animations and feedback
- **Professional Layout**: Clean, organized interface
- **Mobile-Friendly**: Responsive design for all devices

## Usage Guide

### Adding Students
1. Go to "Students" page
2. Click "Add New Student"
3. Fill in name (required), email, and phone
4. Click "Save Student"

### Marking Attendance
1. Go to "Attendance" page
2. Select date (defaults to today)
3. Click "Load Attendance"
4. Click Present/Absent buttons for each student
5. Click "Save All Changes"

### Viewing Reports
1. Go to "Reports" page
2. Select report type:
   - **Student Statistics**: Individual student attendance percentage
   - **Monthly Report**: All students for a specific month
3. Set filters and click "Generate Report"

## API Endpoints
All API endpoints return JSON responses:

### Students
- `GET api/get_students.php` - Get all students
- `POST api/add_student.php` - Add new student
- `POST api/update_student.php` - Update student
- `DELETE api/delete_student.php` - Delete student
- `POST api/check_email.php` - Check email availability

### Attendance
- `GET api/get_attendance.php` - Get attendance data
- `POST api/mark_attendance.php` - Mark attendance
- `GET api/get_student_stats.php` - Get student statistics

### Reports
- `GET api/monthly_report.php` - Get monthly report
- `GET api/reports.php` - Generate reports

## 🧪 Testing

### Bruno API Testing
```bash
# Install Bruno CLI
npm install -g @usebruno/cli

# Run all tests
npx @usebruno/cli run ./bruno_tests --env development

# Run specific test suites
npx @usebruno/cli run ./bruno_tests/students --env development
npx @usebruno/cli run ./bruno_tests/attendance --env development
npx @usebruno/cli run ./bruno_tests/reports --env development
```

## 🐳 Docker Commands

### Basic Operations
```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View service status
docker-compose ps

# View logs
docker-compose logs -f web
docker-compose logs -f database
```

### Database Management
```bash
# Access MySQL shell
docker-compose exec database mysql -u attendance_user -p attendance_system

# Backup database
docker-compose exec database mysqldump -u attendance_user -p attendance_system > backup.sql

# Restore database
docker-compose exec -T database mysql -u attendance_user -p attendance_system < backup.sql
```

## 📋 Additional Resources

- **Detailed Docker Guide**: See `DOCKER_GUIDE.md` for comprehensive Docker setup
- **API Documentation**: Check `docs/API_DOCUMENTATION.md` for endpoint details
- **Testing Guide**: See `docs/TESTING_GUIDELINES.md` for testing procedures
- **User Manual**: Check `docs/USER_MANUAL.md` for usage instructions