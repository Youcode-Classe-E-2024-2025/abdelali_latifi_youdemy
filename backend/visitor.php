<?php
require_once '../backend/courses.php'; 
require_once '../database/connexion.php'; 

class Visitor {
    private $db; 
    private $courses;

    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->getConnection();

            $courseModel = new Course($this->db); 
            $this->courses = $courseModel->getAllCourses();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            $this->courses = [];
        }
    }

    public function getCourses() {
        return $this->courses;
    }

    public function getCourseDetails($course_id) {
        if (!$this->db) {
            throw new Exception("Database connection not initialized.");
        }
    
        $query = "SELECT courses.course_id, 
                         courses.title, 
                         courses.description, 
                         courses.content, 
                         courses.created_at, 
                         categories.name AS category_name 
                  FROM courses 
                  LEFT JOIN categories ON courses.category_id = categories.category_id 
                  WHERE courses.course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    
        $visitor = new Visitor();

        try {
            $courseDetails = $visitor->getCourseDetails($course_id);
            if (!$courseDetails) {
                echo "No course found for ID: " . htmlspecialchars($course_id);
                die();
            }
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
}

    }
    
}
