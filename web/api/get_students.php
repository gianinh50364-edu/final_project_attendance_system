<?php
/**
 * Get Students API Endpoint
 * GET request to retrieve all students or search students
 */

require_once '../config/database.php';
require_once '../classes/Student.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse('error', 'Only GET method allowed');
}

try {
    $student = new Student();
    
    // Check if search parameter is provided
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    if (!empty($search)) {
        $students = $student->searchStudents($search);
        sendJsonResponse('success', 'Students found', $students);
    } else {
        $students = $student->getAllStudents();
        $total_count = $student->getStudentCount();
        
        $response_data = [
            'students' => $students,
            'total_count' => $total_count
        ];
        
        sendJsonResponse('success', 'Students retrieved successfully', $response_data);
    }
    
} catch (Exception $e) {
    error_log("Get students error: " . $e->getMessage());
    sendJsonResponse('error', 'An error occurred while retrieving students');
}
?>