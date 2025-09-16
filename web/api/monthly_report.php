<?php
/**
 * Monthly Report API Endpoint
 * GET request to retrieve monthly attendance report
 */

require_once '../config/database.php';
require_once '../classes/Attendance.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse('error', 'Only GET method allowed');
}

try {
    $attendance = new Attendance();
    
    // Get query parameters
    $month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
    
    // Validate month and year
    if ($month < 1 || $month > 12) {
        sendJsonResponse('error', 'Invalid month. Must be between 1 and 12');
    }
    
    if ($year < 2020 || $year > 2030) {
        sendJsonResponse('error', 'Invalid year. Must be between 2020 and 2030');
    }
    
    // Get monthly report
    $report_data = $attendance->getMonthlyReport($month, $year);
    
    $response_data = [
        'month' => $month,
        'year' => $year,
        'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
        'report' => $report_data
    ];
    
    sendJsonResponse('success', 'Monthly report retrieved successfully', $response_data);
    
} catch (Exception $e) {
    error_log("Monthly report error: " . $e->getMessage());
    sendJsonResponse('error', 'An error occurred while generating the monthly report');
}
?>