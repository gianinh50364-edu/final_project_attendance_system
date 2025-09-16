<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once '../config/database.php';
require_once '../classes/Student.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
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
    if (empty($data['email'])) {
        throw new Exception('Email is required');
    }
    
    $email = trim($data['email']);
    $excludeId = isset($data['exclude_id']) ? (int)$data['exclude_id'] : null;
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Connect to database
    $database = new Database();
    $db = $database->getConnection();
    
    // Create Student instance
    $student = new Student($db);
    
    // Check if email exists
    $emailExists = $student->isEmailExists($email, $excludeId);
    
    if ($emailExists) {
        sendJsonResponse('error', 'Email already exists', ['email_exists' => true]);
    } else {
        sendJsonResponse('success', 'Email is available', ['email_exists' => false]);
    }
    
} catch (Exception $e) {
    error_log("Check email error: " . $e->getMessage());
    sendJsonResponse('error', $e->getMessage());
}
?>