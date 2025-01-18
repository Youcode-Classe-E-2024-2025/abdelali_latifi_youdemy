<?php

require_once '../backend/user.php';

class Etudiant extends User {

    public function __construct() {
        parent::__construct();
    }

    public function enrollCourse($course_id, $student_id) {
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

    public function getMyCourses($student_id) {
        $query = "
            SELECT courses.course_id, courses.title, courses.description 
            FROM enrollments
            INNER JOIN courses ON enrollments.course_id = courses.course_id
            WHERE enrollments.student_id = :student_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
