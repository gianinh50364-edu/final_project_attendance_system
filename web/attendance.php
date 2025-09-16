<?php
$page_title = "Điểm Danh - Hệ Thống Điểm Danh";
require_once 'includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h3 mb-3">
                <i class="bi bi-calendar-check"></i> Điểm Danh
            </h1>
            <p class="text-muted">Ghi nhận điểm danh học sinh cho ngày đã chọn</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-success" id="markAllPresentBtn">
                    <i class="bi bi-check-all"></i> Có Mặt Tất Cả
                </button>
                <button type="button" class="btn btn-danger" id="markAllAbsentBtn">
                    <i class="bi bi-x-octagon"></i> Đánh dấu Vắng Mặt Tất Cả
                </button>
            </div>
        </div>
    </div>

    <!-- Date Selection -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="attendanceDate" class="form-label">Chọn ngày:</label>
            <input type="date" class="form-control" id="attendanceDate" value="<?php echo date('Y-m-d'); ?>">
        </div>
        <div class="col-md-8 d-flex align-items-end">
            <button class="btn btn-primary me-2" id="loadAttendanceBtn">
                <i class="bi bi-arrow-clockwise"></i> Làm mới dữ liệu
            </button>
            <button class="btn btn-success" id="saveAllBtn" style="display: none;">
                <i class="bi bi-save"></i> Lưu tất cả thay đổi
            </button>
        </div>
    </div>

    <!-- Attendance Summary -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0" id="totalStudentsForDate">0</h4>
                    <small>Tổng số học viên</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0" id="presentCount">0</h4>
                    <small>Có mặt</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0" id="absentCount">0</h4>
                    <small>Vắng mặt</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0" id="notMarkedCount">0</h4>
                    <small>Chưa đánh dấu</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Grid -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-people"></i> Điểm Danh Học Sinh
            </h5>
        </div>
        <div class="card-body">
            <div id="attendanceLoading" class="text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading students for attendance...</p>
            </div>
            
            <div id="attendanceGrid" style="display: none;">
                <div class="row" id="studentsGrid">
                    <!-- Student cards will be populated here -->
                </div>
            </div>
            
            <div id="noStudentsForAttendance" style="display: none;" class="text-center py-4">
                <i class="bi bi-people fs-1 text-muted"></i>
                <p class="text-muted mt-2">Không tìm thấy học viên. <a href="students.php">Thêm học viên trước</a></p>
            </div>
        </div>
    </div>
</div>

<script>
let currentDate = '';
let attendanceData = [];
let hasChanges = false;

$(document).ready(function() {
    loadAttendanceForDate();
    
    // Date change handler
    $('#attendanceDate').on('change', function() {
        if (hasChanges) {
            if (confirm('You have unsaved changes. Do you want to load a new date and lose these changes?')) {
                hasChanges = false;
                $('#saveAllBtn').hide();
                loadAttendanceForDate();
            } else {
                $(this).val(currentDate);
            }
        } else {
            loadAttendanceForDate();
        }
    });
    
    // Load attendance button
    $('#loadAttendanceBtn').on('click', function() {
        loadAttendanceForDate();
    });
    
    // Mark all present
    $('#markAllPresentBtn').on('click', function() {
        markAllAttendance('present');
    });
    
    // Mark all absent
    $('#markAllAbsentBtn').on('click', function() {
        markAllAttendance('absent');
    });
    
    // Save all changes
    $('#saveAllBtn').on('click', function() {
        saveAllAttendance();
    });
});

function loadAttendanceForDate() {
    const selectedDate = $('#attendanceDate').val();
    currentDate = selectedDate;
    
    $('#attendanceLoading').show();
    $('#attendanceGrid').hide();
    $('#noStudentsForAttendance').hide();
    
    $.ajax({
        url: 'api/get_attendance.php',
        type: 'GET',
        data: {
            action: 'by_date',
            date: selectedDate
        },
        dataType: 'json',
        success: function(response) {
            $('#attendanceLoading').hide();
            
            if (response.status === 'success') {
                attendanceData = response.data.attendance;
                displayAttendanceGrid(attendanceData);
                updateSummary();
            } else {
                showAlert('Error loading attendance: ' + response.message, 'danger');
                $('#noStudentsForAttendance').show();
            }
        },
        error: function() {
            $('#attendanceLoading').hide();
            $('#noStudentsForAttendance').show();
            showAlert('Error connecting to server', 'danger');
        }
    });
}

function displayAttendanceGrid(students) {
    if (students.length === 0) {
        $('#attendanceGrid').hide();
        $('#noStudentsForAttendance').show();
        return;
    }
    
    let gridHtml = '';
    students.forEach(function(student) {
        const statusClass = student.status === 'present' ? 'success' : 
                          student.status === 'absent' ? 'danger' : 'secondary';
        const statusText = student.status === 'present' ? 'Present' : 
                         student.status === 'absent' ? 'Absent' : 'Not Marked';
        
        gridHtml += `
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card student-card" data-student-id="${student.id}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0">${student.name}</h6>
                            <span class="badge bg-${statusClass}">${statusText}</span>
                        </div>
                        <p class="card-text text-muted small mb-3">${student.email || 'No email'}</p>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-success ${student.status === 'present' ? 'active' : ''}" 
                                    onclick="markStudentAttendance(${student.id}, 'present')">
                                <i class="bi bi-check-lg"></i> Present
                            </button>
                            <button type="button" class="btn btn-outline-danger ${student.status === 'absent' ? 'active' : ''}" 
                                    onclick="markStudentAttendance(${student.id}, 'absent')">
                                <i class="bi bi-x-lg"></i> Absent
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#studentsGrid').html(gridHtml);
    $('#attendanceGrid').show();
    $('#noStudentsForAttendance').hide();
}

function markStudentAttendance(studentId, status) {
    // Update local data
    const studentIndex = attendanceData.findIndex(s => s.id == studentId);
    if (studentIndex !== -1) {
        attendanceData[studentIndex].status = status;
        hasChanges = true;
        $('#saveAllBtn').show();
        
        // Update the card visually
        const card = $(`.student-card[data-student-id="${studentId}"]`);
        const badge = card.find('.badge');
        const buttons = card.find('.btn-group button');
        
        // Update badge
        badge.removeClass('bg-success bg-danger bg-secondary');
        badge.addClass(status === 'present' ? 'bg-success' : 'bg-danger');
        badge.text(status === 'present' ? 'Present' : 'Absent');
        
        // Update buttons
        buttons.removeClass('active');
        buttons.filter(status === 'present' ? '.btn-outline-success' : '.btn-outline-danger').addClass('active');
        
        updateSummary();
    }
}

function markAllAttendance(status) {
    if (!confirm(`Are you sure you want to mark all students as ${status}?`)) {
        return;
    }
    
    attendanceData.forEach(function(student) {
        student.status = status;
    });
    
    hasChanges = true;
    $('#saveAllBtn').show();
    displayAttendanceGrid(attendanceData);
    updateSummary();
    
    showAlert(`All students marked as ${status}`, 'success');
}

function saveAllAttendance() {
    if (!hasChanges) {
        showAlert('No changes to save', 'info');
        return;
    }
    
    const saveBtn = $('#saveAllBtn');
    showLoading(saveBtn);
    
    let savedCount = 0;
    let totalToSave = attendanceData.filter(s => s.status).length;
    
    if (totalToSave === 0) {
        hideLoading(saveBtn);
        showAlert('No attendance records to save', 'warning');
        return;
    }
    
    attendanceData.forEach(function(student) {
        if (student.status) {
            $.ajax({
                url: 'api/mark_attendance.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    student_id: student.id,
                    date: currentDate,
                    status: student.status
                }),
                dataType: 'json',
                success: function(response) {
                    savedCount++;
                    if (savedCount >= totalToSave) {
                        hideLoading(saveBtn);
                        hasChanges = false;
                        $('#saveAllBtn').hide();
                        showAlert('All attendance records saved successfully!', 'success');
                    }
                },
                error: function() {
                    hideLoading(saveBtn);
                    showAlert('Error saving attendance for ' + student.name, 'danger');
                }
            });
        }
    });
}

function updateSummary() {
    const total = attendanceData.length;
    const present = attendanceData.filter(s => s.status === 'present').length;
    const absent = attendanceData.filter(s => s.status === 'absent').length;
    const notMarked = total - present - absent;
    
    $('#totalStudentsForDate').text(total);
    $('#presentCount').text(present);
    $('#absentCount').text(absent);
    $('#notMarkedCount').text(notMarked);
}
</script>

<?php require_once 'includes/footer.php'; ?>