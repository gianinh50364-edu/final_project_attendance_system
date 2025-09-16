<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Only POST requests allowed']);
    exit();
}

require_once '../config/database.php';
require_once '../classes/Attendance.php';

try {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    // Validate required fields
    if (!isset($input['student_id']) || !isset($input['status'])) {
        throw new Exception('Missing required fields: student_id and status');
    }
    
    $student_id = (int)$input['student_id'];
    $status = $input['status'];
    $date = isset($input['date']) ? $input['date'] : date('Y-m-d');
    
    // Validate status (only present and absent are supported in current schema)
    if (!in_array($status, ['present', 'absent'])) {
        throw new Exception('Invalid status. Must be: present or absent');
    }
    
    // Create database connection
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    // Create attendance instance
    $attendance = new Attendance($db);
    
    // Check if attendance already exists for this student and date
    $check_query = "SELECT id FROM attendance WHERE student_id = ? AND date = ?";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute([$student_id, $date]);
    
    if ($check_stmt->rowCount() > 0) {
        // Update existing record
        $update_query = "UPDATE attendance SET status = ? WHERE student_id = ? AND date = ?";
        $update_stmt = $db->prepare($update_query);
        $result = $update_stmt->execute([$status, $student_id, $date]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Attendance updated successfully',
                'action' => 'updated'
            ]);
        } else {
            throw new Exception('Failed to update attendance record');
        }
    } else {
        // Insert new record
        $insert_query = "INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)";
        $insert_stmt = $db->prepare($insert_query);
        $result = $insert_stmt->execute([$student_id, $date, $status]);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Attendance recorded successfully',
                'action' => 'created',
                'id' => $db->lastInsertId()
            ]);
        } else {
            throw new Exception('Failed to insert attendance record');
        }
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>