<?php
/**
 * Student Statistics API Endpoint
 * GET request to retrieve student attendance statistics
 */

require_once '../config/database.php';
require_once '../classes/Attendance.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse('error', 'Only GET method allowed');
}

try {
    $attendance = new Attendance();
    
    // Get query parameters
    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    
    if (empty($student_id)) {
        sendJsonResponse('error', 'Student ID is required');
    }
    
    // Validate date formats if provided
    if (!empty($start_date)) {
        $start_date_obj = DateTime::createFromFormat('Y-m-d', $start_date);
        if (!$start_date_obj || $start_date_obj->format('Y-m-d') !== $start_date) {
            sendJsonResponse('error', 'Invalid start date format. Use YYYY-MM-DD');
        }
    }
    
    if (!empty($end_date)) {
        $end_date_obj = DateTime::createFromFormat('Y-m-d', $end_date);
        if (!$end_date_obj || $end_date_obj->format('Y-m-d') !== $end_date) {
            sendJsonResponse('error', 'Invalid end date format. Use YYYY-MM-DD');
        }
    }
    
    // Get student statistics
    $stats = $attendance->getStudentStats($student_id, $start_date, $end_date);
    
    if (empty($stats)) {
        sendJsonResponse('success', 'No attendance data found for this student', [
            'total_days' => 0,
            'present_days' => 0,
            'absent_days' => 0,
            'attendance_percentage' => 0
        ]);
    } else {
        sendJsonResponse('success', 'Student statistics retrieved successfully', $stats);
    }
    
} catch (Exception $e) {
    error_log("Get student stats error: " . $e->getMessage());
    sendJsonResponse('error', 'An error occurred while retrieving student statistics');
}
?>