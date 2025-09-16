<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/database.php';
require_once '../classes/Student.php';

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    sendJsonResponse('error', 'Method not allowed');
    exit;
}

try {
    // Get input data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid JSON data');
    }
    
    // Validate required fields
    if (empty($data['id'])) {
        throw new Exception('Student ID is required');
    }
    
    if (empty($data['name'])) {
        throw new Exception('Student name is required');
    }
    
    $id = (int)$data['id'];
    $name = trim($data['name']);
    $email = isset($data['email']) ? trim($data['email']) : null;
    $phone = isset($data['phone']) ? trim($data['phone']) : null;
    
    // Validate email format if provided
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Connect to database and create Student instance
    $student = new Student();
    
    // Set student properties
    $student->id = $id;
    $student->name = $name;
    $student->email = $email;
    $student->phone = $phone;
    
    // Validate student data
    $validation_errors = $student->validateStudentData();
    if (!empty($validation_errors)) {
        throw new Exception(implode(', ', $validation_errors));
    }
    
    // Update student
    $result = $student->updateStudent();
    
    if ($result) {
        sendJsonResponse('success', 'Student updated successfully');
    } else {
        throw new Exception('Failed to update student');
    }
    
} catch (Exception $e) {
    error_log("Update student error: " . $e->getMessage());
    
    // Check if it's a duplicate email error
    if (strpos($e->getMessage(), 'Duplicate') !== false || 
        strpos($e->getMessage(), 'email') !== false) {
        http_response_code(409); // Conflict
        sendJsonResponse('error', 'This email is already registered by another student', ['duplicate_email' => true]);
    } else {
        sendJsonResponse('error', $e->getMessage());
    }
}
?>