<?php
/**
 * Reports API Endpoint
 * GET request to retrieve various attendance reports
 */

require_once '../config/database.php';
require_once '../classes/Attendance.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse('error', 'Only GET method allowed');
}

try {
    $attendance = new Attendance();
    
    // Determine which report to generate based on parameters
    if (isset($_GET['action']) && $_GET['action'] === 'daily_summary') {
        // Daily Summary Report
        $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : null;
        $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : null;
        
        if (!$from_date || !$to_date) {
            sendJsonResponse('error', 'from_date and to_date parameters are required for daily summary');
        }
        
        // Validate date format
        if (!DateTime::createFromFormat('Y-m-d', $from_date) || !DateTime::createFromFormat('Y-m-d', $to_date)) {
            sendJsonResponse('error', 'Invalid date format. Use YYYY-MM-DD');
        }
        
        $report_data = $attendance->getDailySummaryReport($from_date, $to_date);
        sendJsonResponse('success', 'Daily summary report retrieved successfully', $report_data);
        
    } elseif (isset($_GET['month'])) {
        // Monthly Report
        $month = $_GET['month'];
        
        // Validate month format (YYYY-MM)
        if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
            sendJsonResponse('error', 'Invalid month format. Use YYYY-MM');
        }
        
        $report_data = $attendance->getComprehensiveMonthlyReport($month);
        sendJsonResponse('success', 'Monthly report retrieved successfully', $report_data);
        
    } else {
        sendJsonResponse('error', 'Please specify either month parameter or action=daily_summary with from_date and to_date');
    }
    
} catch (Exception $e) {
    error_log("Reports error: " . $e->getMessage());
    sendJsonResponse('error', 'An error occurred while generating the report');
}
?>