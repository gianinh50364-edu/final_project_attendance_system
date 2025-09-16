<?php
/**
 * Student Class
 * Handles all student-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Student {
    private $conn;
    private $table = "students";

    // Student properties
    public $id;
    public $name;
    public $email;
    public $phone;
    public $created_at;

    public function __construct() {
        $this->conn = getDatabaseConnection();
    }

    /**
     * Get all students
     * @return array of students
     */
    public function getAllStudents() {
        try {
            $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting students: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get student by ID
     * @param int $id
     * @return array|false
     */
    public function getStudentById($id) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting student by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Add new student
     * @return bool
     */
    public function addStudent() {
        try {
            $query = "INSERT INTO " . $this->table . " (name, email, phone) VALUES (:name, :email, :phone)";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->phone = htmlspecialchars(strip_tags($this->phone));
            
            // Bind parameters
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':phone', $this->phone);
            
            if ($stmt->execute()) {
                $this->id = $this->conn->lastInsertId();
                return true;
            }
            return false;
        } catch(PDOException $e) {
            // Check for duplicate email constraint violation
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'email') !== false) {
                error_log("Duplicate email attempt: " . $this->email);
                throw new Exception("Email address already exists. Please use a different email.");
            }
            error_log("Error adding student: " . $e->getMessage());
            throw new Exception("Database error occurred while adding student.");
        }
    }

    /**
     * Update student
     * @return bool
     */
    public function updateStudent() {
        try {
            $query = "UPDATE " . $this->table . " SET name = :name, email = :email, phone = :phone WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            
            // Sanitize input
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->phone = htmlspecialchars(strip_tags($this->phone));
            $this->id = htmlspecialchars(strip_tags($this->id));
            
            // Bind parameters
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':phone', $this->phone);
            $stmt->bindParam(':id', $this->id);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            // Check for duplicate email constraint violation
            if ($e->getCode() == 23000 && strpos($e->getMessage(), 'email') !== false) {
                error_log("Duplicate email attempt during update: " . $this->email);
                throw new Exception("Email address already exists. Please use a different email.");
            }
            error_log("Error updating student: " . $e->getMessage());
            throw new Exception("Database error occurred while updating student.");
        }
    }

    /**
     * Delete student
     * @param int $id
     * @return bool
     */
    public function deleteStudent($id) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error deleting student: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get student count
     * @return int
     */
    public function getStudentCount() {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch(PDOException $e) {
            error_log("Error getting student count: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Search students
     * @param string $search_term
     * @return array
     */
    public function searchStudents($search_term) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE name LIKE :search OR email LIKE :search OR phone LIKE :search ORDER BY name ASC";
            $stmt = $this->conn->prepare($query);
            $search_param = "%" . $search_term . "%";
            $stmt->bindParam(':search', $search_param);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error searching students: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Validate student data
     * @return array of validation errors
     */
    public function validateStudentData() {
        $errors = [];
        
        if (empty($this->name) || strlen($this->name) < 2) {
            $errors[] = "Name must be at least 2 characters long";
        }
        
        if (empty($this->email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        } elseif ($this->isEmailExists($this->email, $this->id ?? null)) {
            $errors[] = "Email address already exists. Please use a different email.";
        }
        
        if (!empty($this->phone) && !preg_match("/^[\d\-\+\(\)\s]+$/", $this->phone)) {
            $errors[] = "Invalid phone number format";
        }
        
        return $errors;
    }

    /**
     * Check if email already exists (excluding current student for updates)
     * @param string $email
     * @param int|null $exclude_id
     * @return bool
     */
    public function isEmailExists($email, $exclude_id = null) {
        try {
            $query = "SELECT id FROM " . $this->table . " WHERE email = :email";
            if ($exclude_id !== null) {
                $query .= " AND id != :exclude_id";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $email);
            if ($exclude_id !== null) {
                $stmt->bindParam(':exclude_id', $exclude_id);
            }
            
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch(PDOException $e) {
            error_log("Error checking email existence: " . $e->getMessage());
            return false;
        }
    }
}
?>