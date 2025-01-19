<?php

require_once '../backend/user.php';

class Etudiant extends User {

    public function __construct() {
        parent::__construct();
    }

    public function enrollCourse($course_id, $student_id) {
        $query = "SELECT * FROM enrollments WHERE course_id = :course_id AND student_id = :student_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            return false;
        }
            try {
            $query = "INSERT INTO enrollments (course_id, student_id) VALUES (:course_id, :student_id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    

    public function getCoursesByStudent($student_id) {
        try {
            $query = "
                SELECT 
                    courses.course_id, 
                    courses.title, 
                    courses.description, 
                    categories.name AS category_name
                FROM enrollments
                INNER JOIN courses ON enrollments.course_id = courses.course_id
                INNER JOIN categories ON courses.category_id = categories.category_id
                WHERE enrollments.student_id = :student_id
            ";
    
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error retrieving courses: " . $e->getMessage());
        }
    }
     
}
?>
