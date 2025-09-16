<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Hệ Thống Điểm Danh'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-person-check-fill"></i> Hệ Thống Điểm Danh
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">
                            <i class="bi bi-house-door"></i> Trang Chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>" href="students.php">
                            <i class="bi bi-people"></i> Học Sinh
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'attendance.php' ? 'active' : ''; ?>" href="attendance.php">
                            <i class="bi bi-calendar-check"></i> Điểm Danh
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>" href="reports.php">
                            <i class="bi bi-graph-up"></i> Báo Cáo
                        </a>
                    </li>
                </ul>
                
                <div class="navbar-text">
                    <small class="text-light">
                        <i class="bi bi-calendar3"></i> <?php 
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
                        ?>
                    </small>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container-fluid py-4">
        <!-- Alert Container for Messages -->
        <div id="alertContainer"></div>