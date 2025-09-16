# 🧪 TESTING GUIDELINES - ATTENDANCE MANAGEMENT SYSTEM

## 📋 OVERVIEW

This document provides comprehensive testing guidelines for the Attendance Management System. It covers manual testing procedures, automated testing setup, and quality assurance standards to ensure the system functions correctly across all features and scenarios.

### **Testing Scope**
- **Functional Testing**: Core features and user workflows
- **API Testing**: RESTful endpoints using Bruno
- **UI Testing**: User interface and user experience
- **Data Integrity**: Database consistency and validation
- **Performance Testing**: Load and stress testing
- **Security Testing**: Input validation and security measures

---

## 🎯 TESTING OBJECTIVES

### **Primary Goals**
1. **Functionality**: Verify all features work as specified
2. **Reliability**: Ensure system stability under normal conditions
3. **Usability**: Confirm intuitive user experience
4. **Performance**: Validate acceptable response times
5. **Security**: Test data protection and input validation
6. **Compatibility**: Verify cross-browser and device support

### **Quality Standards**
- **Zero Critical Bugs**: No bugs that prevent core functionality
- **<2 Second Response**: Page loads within 2 seconds
- **99.9% Uptime**: System availability during business hours
- **Cross-Browser**: Support for Chrome, Firefox, Safari, Edge
- **Mobile Responsive**: Functional on tablets and phones

---

## 🔧 TESTING ENVIRONMENT SETUP

### **Local Development Environment**

#### **Prerequisites**
```bash
# Required software
- Docker Desktop
- Node.js 16+
- Bruno CLI or GUI
- Git
```

#### **Environment Setup**
```bash
# Clone repository
git clone <repository-url>
cd attendance_sys

# Start services
docker compose up -d

# Verify services are running
docker compose ps

# Install Bruno CLI for API testing
npm install -g @usebruno/cli

# Verify application is accessible
curl http://localhost:8080
```

#### **Test Data Setup**
```bash
# Database should auto-populate with test data
# Verify by checking http://localhost:8080

# Additional test data can be added via API or UI
```


## 🧪 MANUAL TESTING PROCEDURES

### **Pre-Testing Checklist**
- [✅] Application is running (http://localhost:8080 accessible)
- [✅] Database contains test data
- [✅] Browser cache is cleared
- [✅] JavaScript is enabled
- [✅] Test environment is stable

#### **Functional Testing**
- [✅] All CRUD operations work correctly
- [✅] Attendance marking functions properly
- [✅] Reports generate accurately
- [✅] Data validation prevents invalid inputs
- [✅] Error handling works as expected

#### **UI/UX Testing**
- [✅] Navigation is intuitive
- [✅] Forms are user-friendly
- [✅] Responsive design works on all devices
- [✅] Visual feedback is clear
- [✅] Accessibility standards met

#### **API Testing**
- [✅] All endpoints return correct status codes
- [✅] Response formats are consistent
- [✅] Error responses provide useful information
- [✅] API documentation is accurate
- [✅] Rate limiting works (if implemented)

#### **Performance Testing**
- [✅] Page load times under 2 seconds
- [✅] API responses under 500ms
- [✅] System handles expected concurrent users
- [✅] Memory usage within acceptable limits
- [✅] Database queries are optimized

#### **Security Testing**
- [✅] Input validation prevents XSS
- [✅] SQL injection protection works
- [✅] File upload restrictions enforced
- [✅] Session management is secure
- [✅] Sensitive data is protected

#### **Data Integrity**
- [✅] Backup/restore procedures work
- [✅] Data export/import functions correctly
- [✅] Referential integrity maintained
- [✅] Transaction handling is proper

### **Student Management Testing**

#### **Test Case 1: Add New Student**
**Objective**: Verify students can be added successfully

**Steps**:
1. Navigate to Students page
2. Click "Add New Student"
3. Enter valid data:
   - Name: "Test Student 1"
   - Email: "test1@example.com"
   - Phone: "0901234567"
4. Click "Add Student"

**Results**:
- [✅] Success message displays
- [✅] Student appears in list
- [✅] Email validation works
- [✅] Phone format is accepted

#### **Test Case 2: Duplicate Email Validation**
**Objective**: Verify system prevents duplicate emails (fixed)

**Steps**:
1. Try to add student with existing email
2. Submit the form

**Results**:
- [✅] Error message displays
- [✅] Form submission is prevented
- [✅] Original student data is preserved

#### **Test Case 3: Edit Student Information**
**Objective**: Verify student data can be updated (fixed)

**Steps**:
1. Find existing student
2. Click "Edit" button
3. Modify information
4. Save changes

**Results**:
- [✅] Form pre-populates with current data
- [✅] Changes are saved successfully
- [✅] Updated data displays in list

#### **Test Case 4: Delete Student**
**Objective**: Verify student deletion and data integrity

**Steps**:
1. Select student to delete
2. Click "Delete" button
3. Confirm deletion

**Results**:
- [✅] Confirmation dialog appears
- [✅] Student is removed from list
- [✅] Associated attendance records are removed

### **Attendance Management Testing**

#### **Test Case 5: Mark Daily Attendance**
**Objective**: Verify attendance can be marked correctly

**Steps**:
1. Navigate to Attendance page
2. Verify current date is selected
3. Mark students as Present/Absent
4. Verify status saves automatically

**Results**:
- [✅] Attendance status updates immediately
- [✅] Visual feedback is provided
- [✅] Data persists after page refresh

#### **Test Case 6: Date Selection**
**Objective**: Verify attendance can be viewed/marked for different dates

**Steps**:
1. Select past date using date picker
2. View existing attendance
3. Modify attendance status
4. Select future date
5. Mark attendance in advance

**Results**:
- [✅] Date picker functions correctly
- [✅] Past attendance displays accurately
- [✅] Changes save successfully
- [✅] Future dates allow attendance marking

#### **Test Case 7: Batch Attendance Operations**
**Objective**: Verify bulk attendance operations

**Steps**:
1. Click "Mark All Present"
2. Verify all students marked present
3. Click "Mark All Absent"
4. Verify all students marked absent
5. Click "Clear All"
6. Verify all marks removed

**Results**:
- [✅] Batch operations affect all students
- [✅] Individual status can still be changed
- [✅] Operations complete quickly (<5 seconds)

### **Reports Testing**

#### **Test Case 8: Generate Daily Report**
**Objective**: Verify daily reports generate correctly

**Steps**:
1. Navigate to Reports section
2. Select "Daily Report"
3. Choose specific date
4. Generate report

**Results**:
- [✅] Report loads within 1 seconds
- [✅] All students are listed
- [✅] Attendance status is accurate
- [✅] Summary statistics are correct

#### **Test Case 9: Generate Monthly Report**
**Objective**: Verify monthly reports contain comprehensive data

**Steps**:
1. Select "Monthly Report"
2. Choose month/year
3. Generate report
4. Review all sections

**Results**:
- [✅] Report contains daily statistics
- [✅] Student summaries are accurate
- [✅] Attendance rates are calculated correctly
- [✅] Data covers entire month



## AUTOMATED TESTING

### **API Testing with Bruno**

#### **Running All Tests**
```shell
# Run all test case
npx @usebruno/cli run --env development

# Specific modules
npx @usebruno/cli run ./students --env development
npx @usebruno/cli run ./attendance --env development
npx @usebruno/cli run ./reports --env development
```

#### **Test Coverage Verification**
The Bruno test suite covers:
- ✅ Student CRUD operations
- ✅ Attendance marking and retrieval
- ✅ Report generation
- ✅ Data integrity checks

#### **Expected API Test Results**
```
✅ Students Module: 12 tests, all passing
✅ Attendance Module: 10 tests, all passing
✅ Reports Module: 8 tests, all passing

Total: 30 tests, 100% success rate
```

## 🎯 CONCLUSION

This testing guide provides a comprehensive framework for ensuring the Attendance Management System meets quality standards. Regular testing using these procedures will help maintain system reliability and user satisfaction.

### **Key Success Metrics**
- **Zero Critical Bugs**: No system-breaking issues
- **Fast Performance**: <2 second page loads
- **Test Coverage**: >90% of functionality tested

### **Continuous Improvement**
- Regular review and update of test cases
- Automation of repetitive tests
- Performance monitoring and optimization

---
*Last Updated: 15/09/2025*  