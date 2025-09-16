<?php
$page_title = "Trang Chủ - Hệ Thống Điểm Danh";
require_once 'includes/header.php';
?>

<div class="container">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-3">
                <i class="bi bi-speedometer2"></i> Trang Chủ
            </h1>
            <p class="text-muted">Chào mừng đến với Hệ Thống Quản Lý Điểm Danh</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Tổng Học Sinh</h6>
                            <h3 class="mb-0" id="totalStudents">-</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-people fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Có Mặt Hôm Nay</h6>
                            <h3 class="mb-0" id="presentToday">-</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-check fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Vắng Mặt Hôm Nay</h6>
                            <h3 class="mb-0" id="absentToday">-</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-person-x fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Chưa Điểm Danh</h6>
                            <h3 class="mb-0" id="notMarked">-</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-question-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning"></i> Thao Tác Nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="attendance.php" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-check"></i> Điểm Danh
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="students.php" class="btn btn-success w-100">
                                <i class="bi bi-person-plus"></i> Thêm Học Sinh
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="reports.php" class="btn btn-info w-100">
                                <i class="bi bi-graph-up"></i> Xem Báo Cáo
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-secondary w-100" onclick="refreshDashboard()">
                                <i class="bi bi-arrow-clockwise"></i> Làm Mới Dữ Liệu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Attendance Overview -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-day"></i> Điểm Danh Hôm Nay
                    </h5>
                    <span class="badge bg-primary" id="todayDate"><?php 
                        $months = [
                            'January' => 'Tháng 1', 'February' => 'Tháng 2', 'March' => 'Tháng 3',
                            'April' => 'Tháng 4', 'May' => 'Tháng 5', 'June' => 'Tháng 6',
                            'July' => 'Tháng 7', 'August' => 'Tháng 8', 'September' => 'Tháng 9',
                            'October' => 'Tháng 10', 'November' => 'Tháng 11', 'December' => 'Tháng 12'
                        ];
                        $date = date('F, d, Y');
                        foreach($months as $en => $vi) {
                            $date = str_replace($en, $vi, $date);
                        }
                        echo $date;
                    ?></span>
                </div>
                <div class="card-body">
                    <div id="todayAttendanceLoading" class="text-center py-4">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2 text-muted">Đang tải dữ liệu điểm danh hôm nay...</p>
                    </div>
                    <div id="todayAttendanceTable" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Học Sinh</th>
                                        <th>Email</th>
                                        <th>Trạng Thái</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="noAttendanceData" style="display: none;" class="text-center py-4">
                        <i class="bi bi-clipboard-x fs-1 text-muted"></i>
                        <p class="text-muted mt-2">Chưa có dữ liệu điểm danh hôm nay. <a href="attendance.php">Điểm danh ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle"></i> Thông Tin Hệ Thống
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Database Status</h6>
                        <span class="badge bg-success">Connected</span>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Last Update</h6>
                        <small class="text-muted" id="lastUpdate">Loading...</small>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">Features</h6>
                        <ul class="list-unstyled small">
                            <li><i class="bi bi-check-circle text-success"></i> Student Management</li>
                            <li><i class="bi bi-check-circle text-success"></i> Attendance Tracking</li>
                            <li><i class="bi bi-check-circle text-success"></i> Report Generation</li>
                            <li><i class="bi bi-check-circle text-success"></i> AJAX Interface</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadDashboardData();
    setInterval(loadDashboardData, 300000); // Refresh every 5 minutes
});

function loadDashboardData() {
    // Load today's summary
    $.ajax({
        url: 'api/get_attendance.php',
        type: 'GET',
        data: { action: 'today_summary' },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const data = response.data;
                $('#totalStudents').text(data.total_students || 0);
                $('#presentToday').text(data.present_count || 0);
                $('#absentToday').text(data.absent_count || 0);
                $('#notMarked').text(data.not_marked_count || 0);
            }
        },
        error: function() {
            showAlert('Error loading dashboard summary', 'danger');
        }
    });
    
    // Load today's attendance details
    const today = new Date().toISOString().split('T')[0];
    $.ajax({
        url: 'api/get_attendance.php',
        type: 'GET',
        data: { 
            action: 'by_date',
            date: today
        },
        dataType: 'json',
        success: function(response) {
            $('#todayAttendanceLoading').hide();
            
            if (response.status === 'success' && response.data.attendance.length > 0) {
                const attendanceData = response.data.attendance;
                let tableRows = '';
                
                attendanceData.forEach(function(record) {
                    const statusBadge = record.status === 'present' 
                        ? '<span class="badge bg-success">Present</span>'
                        : record.status === 'absent'
                        ? '<span class="badge bg-danger">Absent</span>'
                        : '<span class="badge bg-secondary">Not Marked</span>';
                    
                    tableRows += `
                        <tr>
                            <td>${record.name}</td>
                            <td>${record.email || '-'}</td>
                            <td>${statusBadge}</td>
                        </tr>
                    `;
                });
                
                $('#attendanceTableBody').html(tableRows);
                $('#todayAttendanceTable').show();
            } else {
                $('#noAttendanceData').show();
            }
        },
        error: function() {
            $('#todayAttendanceLoading').hide();
            $('#noAttendanceData').show();
            showAlert('Error loading today\'s attendance', 'danger');
        }
    });
    
    // Update last update time
    $('#lastUpdate').text(new Date().toLocaleString());
}

function refreshDashboard() {
    showAlert('Refreshing dashboard data...', 'info', 2000);
    loadDashboardData();
}
</script>

<?php require_once 'includes/footer.php'; ?>