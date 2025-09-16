<?php
/**
 * Get Attendance API Endpoint
 * GET request to retrieve attendance data
 */

require_once '../config/database.php';
require_once '../classes/Attendance.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse('error', 'Only GET method allowed');
}

try {
    $attendance = new Attendance();
    
    // Get query parameters
    $date = isset($_GET['date']) ? $_GET['date'] : '';
    $student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : 'by_date';
    
    switch ($action) {
        case 'by_date':
            if (empty($date)) {
                $date = date('Y-m-d'); // Default to today
            }
            
            // Validate date format
            $date_obj = DateTime::createFromFormat('Y-m-d', $date);
            if (!$date_obj || $date_obj->format('Y-m-d') !== $date) {
                sendJsonResponse('error', 'Invalid date format. Use YYYY-MM-DD');
            }
            
            $data = $attendance->getAttendanceByDate($date);
            sendJsonResponse('success', 'Attendance retrieved successfully', [
                'date' => $date,
                'attendance' => $data
            ]);
            break;
            
        case 'student_history':
            if (empty($student_id)) {
                sendJsonResponse('error', 'Student ID is required for student history');
            }
            
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 30;
            $data = $attendance->getStudentAttendance($student_id, $limit);
            sendJsonResponse('success', 'Student attendance history retrieved', $data);
            break;
            
        case 'today_summary':
            $data = $attendance->getTodaysSummary();
            sendJsonResponse('success', 'Today\'s summary retrieved', $data);
            break;
            
        case 'date_range':
            $data = $attendance->getAttendanceDateRange();
            sendJsonResponse('success', 'Date range retrieved', $data);
            break;
            
        default:
            sendJsonResponse('error', 'Invalid action specified');
    }
    
} catch (Exception $e) {
    error_log("Get attendance error: " . $e->getMessage());
    sendJsonResponse('error', 'An error occurred while retrieving attendance data');
}
?>