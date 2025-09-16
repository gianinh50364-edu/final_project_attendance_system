# 📚 ATTENDANCE MANAGEMENT SYSTEM - PROJECT DOCUMENTATION

## 🎯 PROJECT OVERVIEW

### **Description**
_(In development)_
Attendance management system is developed to support schools and training centers in managing and tracking student attendance efficiently and accurately.

### **Objectives**
- **Automate** traditional attendance processes
- **Save time** for teachers and administrators
- **Monitor** student learning progress
- **Generate** detailed and visual statistical reports
- **Store** data securely and safely

### **Target Users**
- **Teachers**: Daily attendance marking, view reports
- **Administrators**: Overall monitoring, data analysis
- **IT Admin**: System management, data backup

## SYSTEM ARCHITECTURE

### **Technology Stack**
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (jQuery)
- **Backend**: PHP 8.1 with Apache Web Server
- **Database**: MySQL 8.0
- **Containerization**: Docker & Docker Compose
- **Version Control**: Git
- **Development**: VS Code, Docker Desktop

### **3-Tier Architecture**
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Presentation  │    │   Application   │    │      Data       │
│     Layer       │    │     Layer       │    │     Layer       │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • HTML/CSS/JS   │◄──►│ • PHP Classes   │◄──►│ • MySQL 8.0     │
│ • Bootstrap 5   │    │ • RESTful API   │    │ • Database      │
│ • Responsive    │    │ • Business      │    │ • Persistent    │
│   Design        │    │   Logic         │    │   Storage       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### **Database Schema**
```sql
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

## KEY FEATURES

### **1. Student Management**
- ✅ Add, edit, delete student information
- ✅ Search and filter student lists
- ✅ Import/Export data (CSV)
- ✅ Input data validation

### **2. Attendance Tracking**
- ✅ Daily attendance marking
- ✅ Mark present/absent status
- ✅ Batch attendance marking
- ✅ Attendance history storage

### **3. Reports & Statistics**
- ✅ Overview dashboard
- ✅ Daily/monthly reports
- ✅ Attendance rate statistics
- ✅ Export reports to PDF/Excel


## 📁 DIRECTORY STRUCTURE

```
attendance_sys/
├── 📁 web/                                 # Web application source code
│   ├── 📄 index.php                        # Main dashboard page
│   ├── 📄 students.php                     # Student management
│   ├── 📄 attendance.php                   # Attendance interface
│   ├── 📄 reports.php                      # Reports and statistics
│   ├── 📁 api/                             # RESTful API endpoints
│   │   ├── 📄 add_student.php              # Student CRUD operations
│   │   ├── 📄 check_email.php              # Email validation operations
│   │   ├── 📄 delete_student.php           # Delete student operations
│   │   ├── 📄 get_attendance.php           # Show all attendance today
│   │   ├── 📄 get_student_stats.php        # Show student stats
│   │   ├── 📄 get_students.php             # Student CRUD operations
│   │   ├── 📄 mark_attendance.php          # Attendance operations
│   │   ├── 📄 monthly_report.php           # Reports and statistics
│   │   ├── 📄 reports.php                  # Report generation
│   │   └── 📄 update_student.php           # Update student operations
│   ├── 📁 classes/                         # PHP Classes
│   │   ├── 📄 Student.php                  # Student management class
│   │   └── 📄 Attendance.php               # Attendance management class
│   ├── 📁 config/                          # System configuration
│   │   └── 📄 database.php                 # Database connection
│   ├── 📁 assets/                          # Static resources
│   │   ├── 📁 css/                         # Stylesheets
│   │   └── 📁 js/                          # JavaScript files
│   └── 📁 includes/                        # Shared components
│       ├── 📄 header.php                   # Common header
│       └── 📄 footer.php                   # Common footer
├── 📁 bruno_tests/                         # Bruno API testing collection
│   ├── 📄 bruno.json                       # Collection configuration
│   ├── 📁 environments/                    # Environment variables
│   │   └── 📄 development.bru              # Development config
│   ├── 📁 students/                        # Student API tests
│   │   ├── 📄 get_all_students.bru         # Get students test
│   │   ├── 📄 add_student.bru              # Add student test
│   │   ├── 📄 update_student.bru           # Update student test
│   │   └── 📄 delete_student.bru           # Delete student test
│   ├── 📁 attendance/                      # Attendance API tests
│   │   ├── 📄 get_student_stats.bru        # Student statistics test
│   │   ├── 📄 mark_attendance_absent.bru   # Mark absent attendance test
│   │   ├── 📄 mark_attendance.bru          # Mark attendance test
│   │   └── 📄 get_attendance_by_date.bru   # Get attendance test
│   └── 📁 reports/                         # Reports API tests
│       ├── 📄 daily_summary_report.bru     # Daily report test
│       └── 📄 monthly_report.bru           # Monthly report test
├── 📄 docker-compose.yml                   # Docker setup
├── 📄 schema.sql                           # Database schema
├── 📄 deploy.sh                            # Linux deployment script
├── 📄 deploy.bat                           # Windows deployment script
├── 📄 DOCKER_GUIDE.md                      # Detailed application's services
├── 📄 README.md                            # Project overview
├── 📄 SETUP.md                             # Deployment guide
└── 📁 docs/                                # Project documentation
    ├── 📄 API_DOCUMENTATION.md             # API documentation
    ├── 📄 DATABASE_SCHEMA.md               # Database description
    ├── 📄 TESING_GUIDELINES.md             # Testing documentation
    └── 📄 USER_MANUAL.md                   # User manual
```

## ⚙️ DEPLOYMENT

### **Quick Installation**
```bash
# Clone repository
git clone <repository-url>
cd attendance_sys

# Start with Docker
docker compose up -d

# Access application
# http://localhost:8080

# Or visit SETUP.md
```
### **Access the Application**
- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

## 🧪 TESTING

### **Testing Strategy**
- **Unit Tests**: Individual function testing
- **Integration Tests**: API endpoint testing  
- **User Acceptance Tests**: End-to-end scenarios
- **Performance Tests**: Load and stress testing

### **Testing Tools**
- **Bruno**: API testing and documentation

#### **Bruno API Testing**
Bruno is a modern API testing tool that provides:
- ✅ **Collection-based testing**: Organize tests by feature
- ✅ **Environment management**: Dev/staging/production configs
- ✅ **Pre/Post request scripts**: Advanced testing scenarios
- ✅ **Git-friendly**: Version control your API tests
- ✅ **Documentation**: Auto-generated API documentation


**Available Test Collections:**
- 🎓 **Student Management**: CRUD operations, validation testing
- 📅 **Attendance Tracking**: Mark attendance, get daily reports
- 📊 **Reports & Analytics**: Monthly reports, student statistics


**Test Coverage:**
- ✅ 30 API endpoint tests
- ✅ Validation error scenarios
- ✅ Data integrity checks
- ✅ Response format validation
- ✅ Performance benchmarks

*Documentation Updated: 15/09/2025*