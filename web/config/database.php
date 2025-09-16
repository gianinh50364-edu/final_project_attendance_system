<?php
/**
 * Database Configuration
 * Attendance Management System
 */

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $connection;

    public function __construct() {
        // Use environment variables for Docker, fallback to defaults for local development
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->db_name = getenv('DB_NAME') ?: "attendance_system";
        $this->username = getenv('DB_USER') ?: "root";
        $this->password = getenv('DB_PASS') ?: "";
    }

    /**
     * Get database connection
     * @return PDO connection object
     */
    public function getConnection() {
        $this->connection = null;

        try {
            $this->connection = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->connection;
    }
}

/**
 * Helper function to get database connection
 * @return PDO connection object
 */
function getDatabaseConnection() {
    $database = new Database();
    return $database->getConnection();
}

/**
 * Set JSON response headers
 */
function setJsonHeaders() {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}

/**
 * Send JSON response
 * @param string $status - success or error
 * @param string $message - response message
 * @param array $data - optional data array
 * @param array $error - optional error details
 */
function sendJsonResponse($status, $message, $data = null, $error = null) {
    setJsonHeaders();
    $response = array(
        "success" => ($status === 'success'),
        "status" => $status,
        "message" => $message
    );
    if ($data !== null) {
        $response["data"] = $data;
    }
    if ($error !== null) {
        $response["error"] = $error;
    }
    echo json_encode($response);
    exit();
}
?>