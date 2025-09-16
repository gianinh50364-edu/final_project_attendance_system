<?php
$page_title = "Reports - Attendance System";
require_once 'includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">
                <i class="bi bi-graph-up"></i> Báo cáo điểm danh
            </h1>
            <p class="text-muted">Xem thống kê và báo cáo điểm danh chi tiết</p>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="bi bi-funnel"></i> Report Filters
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="reportType" class="form-label">Loại báo cáo</label>
                    <select class="form-select" id="reportType">
                        <option value="student_stats">Thống kê sinh viên</option>
                        <option value="monthly_report">Báo cáo theo tháng</option>
                    </select>
                </div>
                <div class="col-md-3" id="studentSelectDiv">
                    <label for="studentSelect" class="form-label">Chọn sinh viên</label>
                    <select class="form-select" id="studentSelect">
                        <option value="">Loading students...</option>
                    </select>
                </div>
                <div class="col-md-2" id="monthSelectDiv" style="display: none;">
                    <label for="monthSelect" class="form-label">Month</label>
                    <select class="form-select" id="monthSelect">
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="col-md-2" id="yearSelectDiv" style="display: none;">
                    <label for="yearSelect" class="form-label">Year</label>
                    <select class="form-select" id="yearSelect">
                        <option value="2024">2024</option>
                        <option value="2025" selected>2025</option>
                        <option value="2026">2026</option>
                    </select>
                </div>
                <div class="col-md-2" id="startDateDiv" style="display: none;">
                    <label for="startDate" class="form-label">Ngày bắt đầu</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="col-md-2" id="endDateDiv" style="display: none;">
                    <label for="endDate" class="form-label">Ngày kết thúc</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="generateReportBtn">
                        <i class="bi bi-graph-up-arrow"></i> Trích xuất báo cáo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Results -->
    <div id="reportResults">
        <!-- Student Statistics Report -->
        <div class="card" id="studentStatsReport" style="display: none;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-person-badge"></i> Thống kê điểm danh
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0" id="statsTotalDays">0</h4>
                                <small>Tổng số ngày</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0" id="statsPresentDays">0</h4>
                                <small>Ngày có mặt</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0" id="statsAbsentDays">0</h4>
                                <small>Ngày vắng mặt</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h4 class="mb-0" id="statsPercentage">0%</h4>
                                <small>Tỷ lệ điểm danh %</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" id="attendanceProgressBar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Monthly Report -->
        <div class="card" id="monthlyReport" style="display: none;">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-month"></i> Báo cáo điểm danh theo tháng
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Total Days</th>
                                <th>Present Days</th>
                                <th>Absent Days</th>
                                <th>Attendance %</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="monthlyReportTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div class="card" id="reportLoading" style="display: none;">
            <div class="card-body text-center py-4">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Generating report...</p>
            </div>
        </div>

        <!-- No Data -->
        <div class="card" id="noReportData" style="display: none;">
            <div class="card-body text-center py-4">
                <i class="bi bi-graph-down fs-1 text-muted"></i>
                <p class="text-muted mt-2">No data found for the selected criteria</p>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadStudentsForReport();
    
    // Set default month and year
    const currentDate = new Date();
    $('#monthSelect').val(currentDate.getMonth() + 1);
    $('#yearSelect').val(currentDate.getFullYear());
    
    // Report type change handler
    $('#reportType').on('change', function() {
        toggleReportFilters();
    });
    
    // Generate report button
    $('#generateReportBtn').on('click', function() {
        generateReport();
    });
    
    // Check for student_id in URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const studentId = urlParams.get('student_id');
    if (studentId) {
        setTimeout(() => {
            $('#studentSelect').val(studentId);
            generateReport();
        }, 1000);
    }
});

function loadStudentsForReport() {
    $.ajax({
        url: 'api/get_students.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                let options = '<option value="">Select a student...</option>';
                response.data.students.forEach(function(student) {
                    options += `<option value="${student.id}">${student.name}</option>`;
                });
                $('#studentSelect').html(options);
            }
        },
        error: function() {
            $('#studentSelect').html('<option value="">Error loading students</option>');
        }
    });
}

function toggleReportFilters() {
    const reportType = $('#reportType').val();
    
    // Hide all filter divs
    $('#studentSelectDiv, #monthSelectDiv, #yearSelectDiv, #startDateDiv, #endDateDiv').hide();
    
    switch(reportType) {
        case 'student_stats':
            $('#studentSelectDiv, #startDateDiv, #endDateDiv').show();
            break;
        case 'monthly_report':
            $('#monthSelectDiv, #yearSelectDiv').show();
            break;
        case 'date_range':
            $('#startDateDiv, #endDateDiv').show();
            break;
    }
}

function generateReport() {
    const reportType = $('#reportType').val();
    
    // Hide all report sections
    $('.card:not(#reportLoading)').hide();
    $('#reportLoading').show();
    
    switch(reportType) {
        case 'student_stats':
            generateStudentStats();
            break;
        case 'monthly_report':
            generateMonthlyReport();
            break;
        case 'date_range':
            generateDateRangeReport();
            break;
    }
}

function generateStudentStats() {
    const studentId = $('#studentSelect').val();
    const startDate = $('#startDate').val();
    const endDate = $('#endDate').val();
    
    if (!studentId) {
        $('#reportLoading').hide();
        showAlert('Please select a student', 'warning');
        return;
    }
    
    let url = `api/get_student_stats.php?student_id=${studentId}`;
    if (startDate && endDate) {
        url += `&start_date=${startDate}&end_date=${endDate}`;
    }
    
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#reportLoading').hide();
            
            if (response.status === 'success') {
                const stats = response.data;
                
                $('#statsTotalDays').text(stats.total_days || 0);
                $('#statsPresentDays').text(stats.present_days || 0);
                $('#statsAbsentDays').text(stats.absent_days || 0);
                $('#statsPercentage').text((stats.attendance_percentage || 0) + '%');
                $('#attendanceProgressBar').css('width', (stats.attendance_percentage || 0) + '%');
                
                // Change progress bar color based on percentage
                const percentage = stats.attendance_percentage || 0;
                $('#attendanceProgressBar').removeClass('bg-success bg-warning bg-danger');
                if (percentage >= 80) {
                    $('#attendanceProgressBar').addClass('bg-success');
                } else if (percentage >= 60) {
                    $('#attendanceProgressBar').addClass('bg-warning');
                } else {
                    $('#attendanceProgressBar').addClass('bg-danger');
                }
                
                $('#studentStatsReport').show();
            } else {
                $('#noReportData').show();
                showAlert('Error: ' + response.message, 'danger');
            }
        },
        error: function() {
            $('#reportLoading').hide();
            $('#noReportData').show();
            showAlert('Error connecting to server', 'danger');
        }
    });
}

function generateMonthlyReport() {
    const month = $('#monthSelect').val();
    const year = $('#yearSelect').val();
    
    $.ajax({
        url: `api/monthly_report.php?month=${month}&year=${year}`,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#reportLoading').hide();
            
            if (response.status === 'success' && response.data.report.length > 0) {
                let tableRows = '';
                response.data.report.forEach(function(student) {
                    const percentage = student.attendance_percentage || 0;
                    let statusBadge = '';
                    
                    if (percentage >= 80) {
                        statusBadge = '<span class="badge bg-success">Good</span>';
                    } else if (percentage >= 60) {
                        statusBadge = '<span class="badge bg-warning">Average</span>';
                    } else {
                        statusBadge = '<span class="badge bg-danger">Poor</span>';
                    }
                    
                    tableRows += `
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.total_days || 0}</td>
                            <td>${student.present_days || 0}</td>
                            <td>${student.absent_days || 0}</td>
                            <td>${percentage}%</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                });
                
                $('#monthlyReportTableBody').html(tableRows);
                $('#monthlyReport').show();
            } else {
                $('#noReportData').show();
                if (response.message) {
                    showAlert('Error: ' + response.message, 'danger');
                }
            }
        },
        error: function() {
            $('#reportLoading').hide();
            $('#noReportData').show();
            showAlert('Error connecting to server', 'danger');
        }
    });
}

function generateDateRangeReport() {
    // This would be similar to monthly report but with date range
    $('#reportLoading').hide();
    $('#noReportData').show();
    showAlert('Date range report feature would be implemented with additional API endpoint', 'info');
}

// Initialize filters on page load
toggleReportFilters();
</script>

<?php require_once 'includes/footer.php'; ?>