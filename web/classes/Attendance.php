<?php
/**
 * Attendance Class
 * Handles all attendance-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Attendance {
    private $conn;
    private $table = "attendance";

    // Attendance properties
    public $id;
    public $student_id;
    public $date;
    public $status;
    public $created_at;

    public function __construct() {
        $this->conn = getDatabaseConnection();
    }

    /**
     * Mark attendance for a student
     * @return bool
     */
    public function markAttendance() {
        try {
            // Check if attendance already exists for this student and date
            $check_query = "SELECT id FROM " . $this->table . " WHERE student_id = :student_id AND date = :date";
            $check_stmt = $this->conn->prepare($check_query);
            $check_stmt->bindParam(':student_id', $this->student_id);
            $check_stmt->bindParam(':date', $this->date);
            $check_stmt->execute();
            
            if ($check_stmt->rowCount() > 0) {
                // Update existing record
                $query = "UPDATE " . $this->table . " SET status = :status WHERE student_id = :student_id AND date = :date";
            } else {
                // Insert new record
                $query = "INSERT INTO " . $this->table . " (student_id, date, status) VALUES (:student_id, :date, :status)";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':student_id', $this->student_id);
            $stmt->bindParam(':date', $this->date);
            $stmt->bindParam(':status', $this->status);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error marking attendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get attendance for a specific date
     * @param string $date
     * @return array
     */
    public function getAttendanceByDate($date) {
        try {
            $query = "SELECT s.id, s.name, s.email, a.status, a.date 
                     FROM students s 
                     LEFT JOIN " . $this->table . " a ON s.id = a.student_id AND a.date = :date 
                     ORDER BY s.name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting attendance by date: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get attendance for a specific student
     * @param int $student_id
     * @param int $limit
     * @return array
     */
    public function getStudentAttendance($student_id, $limit = 30) {
        try {
            $query = "SELECT * FROM " . $this->table . " WHERE student_id = :student_id ORDER BY date DESC LIMIT :limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting student attendance: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get attendance statistics for a student
     * @param int $student_id
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function getStudentStats($student_id, $start_date = null, $end_date = null) {
        try {
            $where_clause = "WHERE student_id = :student_id";
            $params = [':student_id' => $student_id];
            
            if ($start_date && $end_date) {
                $where_clause .= " AND date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $start_date;
                $params[':end_date'] = $end_date;
            }
            
            $query = "SELECT 
                        COUNT(*) as total_days,
                        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_days,
                        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                        ROUND((SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as attendance_percentage
                      FROM " . $this->table . " " . $where_clause;
            
            $stmt = $this->conn->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting student stats: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get today's attendance summary
     * @return array
     */
    public function getTodaysSummary() {
        try {
            $today = date('Y-m-d');
            $query = "SELECT 
                        COUNT(DISTINCT s.id) as total_students,
                        SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_count,
                        SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                        COUNT(DISTINCT s.id) - COUNT(a.id) as not_marked_count
                      FROM students s 
                      LEFT JOIN " . $this->table . " a ON s.id = a.student_id AND a.date = :today";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':today', $today);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting today's summary: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get monthly attendance report
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getMonthlyReport($month, $year) {
        try {
            $query = "SELECT 
                        s.id, s.name,
                        COUNT(a.id) as total_days,
                        SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_days,
                        SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                        ROUND((SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) / COUNT(a.id)) * 100, 2) as attendance_percentage
                      FROM students s 
                      LEFT JOIN " . $this->table . " a ON s.id = a.student_id 
                      WHERE MONTH(a.date) = :month AND YEAR(a.date) = :year
                      GROUP BY s.id, s.name
                      ORDER BY s.name ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting monthly report: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark all students as present/absent for a date
     * @param string $date
     * @param string $status
     * @return bool
     */
    public function markAllAttendance($date, $status) {
        try {
            $this->conn->beginTransaction();
            
            // Get all students
            $student_query = "SELECT id FROM students";
            $student_stmt = $this->conn->prepare($student_query);
            $student_stmt->execute();
            $students = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($students as $student) {
                $this->student_id = $student['id'];
                $this->date = $date;
                $this->status = $status;
                
                if (!$this->markAttendance()) {
                    $this->conn->rollBack();
                    return false;
                }
            }
            
            $this->conn->commit();
            return true;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            error_log("Error marking all attendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get attendance date range
     * @return array
     */
    public function getAttendanceDateRange() {
        try {
            $query = "SELECT MIN(date) as min_date, MAX(date) as max_date FROM " . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Error getting date range: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Delete attendance record
     * @param int $student_id
     * @param string $date
     * @return bool
     */
    public function deleteAttendance($student_id, $date) {
        try {
            $query = "DELETE FROM " . $this->table . " WHERE student_id = :student_id AND date = :date";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':date', $date);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Error deleting attendance: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get comprehensive monthly report with all statistics
     * @param string $month_year Format: YYYY-MM
     * @return array
     */
    public function getComprehensiveMonthlyReport($month_year) {
        try {
            $year = substr($month_year, 0, 4);
            $month = substr($month_year, 5, 2);
            
            // Get total students
            $student_query = "SELECT COUNT(*) as total_students FROM students";
            $student_stmt = $this->conn->prepare($student_query);
            $student_stmt->execute();
            $total_students = $student_stmt->fetch(PDO::FETCH_ASSOC)['total_students'];
            
            // Get overall statistics
            $overall_query = "SELECT 
                                COUNT(*) as total_attendance_records,
                                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                                ROUND((SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as overall_rate
                              FROM " . $this->table . " 
                              WHERE MONTH(date) = :month AND YEAR(date) = :year";
            $overall_stmt = $this->conn->prepare($overall_query);
            $overall_stmt->bindParam(':month', $month);
            $overall_stmt->bindParam(':year', $year);
            $overall_stmt->execute();
            $overall_stats = $overall_stmt->fetch(PDO::FETCH_ASSOC);
            
            // Get daily statistics
            $daily_query = "SELECT 
                              date,
                              COUNT(*) as total_records,
                              SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
                              SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                              ROUND((SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as rate
                            FROM " . $this->table . " 
                            WHERE MONTH(date) = :month AND YEAR(date) = :year
                            GROUP BY date
                            ORDER BY date ASC";
            $daily_stmt = $this->conn->prepare($daily_query);
            $daily_stmt->bindParam(':month', $month);
            $daily_stmt->bindParam(':year', $year);
            $daily_stmt->execute();
            $daily_stats = $daily_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get student summary
            $student_summary_query = "SELECT 
                                        s.id, s.name, s.email,
                                        COUNT(a.id) as total_days,
                                        SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) as present_days,
                                        SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                                        ROUND((SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) / COUNT(a.id)) * 100, 2) as attendance_percentage
                                      FROM students s 
                                      LEFT JOIN " . $this->table . " a ON s.id = a.student_id 
                                      WHERE MONTH(a.date) = :month AND YEAR(a.date) = :year
                                      GROUP BY s.id, s.name, s.email
                                      ORDER BY s.name ASC";
            $student_stmt = $this->conn->prepare($student_summary_query);
            $student_stmt->bindParam(':month', $month);
            $student_stmt->bindParam(':year', $year);
            $student_stmt->execute();
            $student_summary = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'month' => $month_year,
                'total_students' => $total_students,
                'overall_stats' => $overall_stats,
                'daily_stats' => $daily_stats,
                'student_summary' => $student_summary
            ];
            
        } catch(PDOException $e) {
            error_log("Error getting comprehensive monthly report: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get daily summary report for a date range
     * @param string $from_date Format: YYYY-MM-DD
     * @param string $to_date Format: YYYY-MM-DD
     * @return array
     */
    public function getDailySummaryReport($from_date, $to_date) {
        try {
            $query = "SELECT 
                        date,
                        COUNT(*) as total_records,
                        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent,
                        ROUND((SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) as rate
                      FROM " . $this->table . " 
                      WHERE date BETWEEN :from_date AND :to_date
                      GROUP BY date
                      ORDER BY date ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':from_date', $from_date);
            $stmt->bindParam(':to_date', $to_date);
            $stmt->execute();
            $daily_summaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'date_range' => [
                    'from' => $from_date,
                    'to' => $to_date
                ],
                'daily_summaries' => $daily_summaries
            ];
            
        } catch(PDOException $e) {
            error_log("Error getting daily summary report: " . $e->getMessage());
            return [];
        }
    }
}
?>