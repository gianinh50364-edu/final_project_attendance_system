<?php
/**
 * Add Student API Endpoint
 * POST request to add a new student
 */

require_once '../config/database.php';
require_once '../classes/Student.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse('error', 'Only POST method allowed');
}

try {
    $student = new Student();
    
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    // Validate required fields
    if (empty($input['name'])) {
        http_response_code(400);
        sendJsonResponse('error', 'Name is required');
    }
    
    if (empty($input['email'])) {
        http_response_code(400);
        sendJsonResponse('error', 'Email is required');
    }
    
    // Set student properties
    $student->name = $input['name'];
    $student->email = $input['email'];
    $student->phone = isset($input['phone']) ? $input['phone'] : '';
    
    // Validate student data
    $validation_errors = $student->validateStudentData();
    if (!empty($validation_errors)) {
        http_response_code(400);
        sendJsonResponse('error', implode(', ', $validation_errors), null, [
            'code' => 'VALIDATION_ERROR',
            'details' => $validation_errors
        ]);
    }
    
    // Add student
    if ($student->addStudent()) {
        $new_student = $student->getStudentById($student->id);
        sendJsonResponse('success', 'Student added successfully', $new_student);
    } else {
        http_response_code(500);
        sendJsonResponse('error', 'Failed to add student');
    }
    
} catch (Exception $e) {
    error_log("Add student error: " . $e->getMessage());
    
    // Handle specific error types
    if (strpos($e->getMessage(), 'Email address already exists') !== false) {
        http_response_code(400);
        sendJsonResponse('error', $e->getMessage(), null, [
            'code' => 'DUPLICATE_EMAIL',
            'field' => 'email'
        ]);
    } else {
        http_response_code(500);
        sendJsonResponse('error', 'An error occurred while adding the student');
    }
}
?>