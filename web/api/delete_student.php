<?php
/**
 * Delete Student API Endpoint
 * DELETE request to remove a student
 */

require_once '../config/database.php';
require_once '../classes/Student.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Only DELETE or POST method allowed');
}

try {
    $student = new Student();
    
    // Get request data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    // Get student ID from URL parameter or request body
    $student_id = isset($_GET['id']) ? $_GET['id'] : (isset($input['id']) ? $input['id'] : null);
    
    if (empty($student_id)) {
        sendJsonResponse('error', 'Student ID is required');
    }
    
    // Check if student exists
    $existing_student = $student->getStudentById($student_id);
    if (!$existing_student) {
        sendJsonResponse('error', 'Student not found');
    }
    
    // Delete student
    if ($student->deleteStudent($student_id)) {
        sendJsonResponse('success', 'Student deleted successfully');
    } else {
        sendJsonResponse('error', 'Failed to delete student');
    }
    
} catch (Exception $e) {
    error_log("Delete student error: " . $e->getMessage());
    sendJsonResponse('error', 'An error occurred while deleting the student');
}
?>