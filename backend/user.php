<?php

require_once '../database/connexion.php';

class User extends Database {
    protected $db;

    public function __construct() {
        $this->db = $this->getConnection();
    }

    public function getCourses() {
        $query = "SELECT course_id, title, description FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseDetails($course_id) {
        $query = "
            SELECT courses.course_id, courses.title, courses.description, courses.content, categories.name AS category_name
            FROM courses
            LEFT JOIN categories ON courses.category_id = categories.category_id
            WHERE courses.course_id = :course_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   

    public function getCategories() {
        $query = "SELECT category_id, name FROM categories";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
?>
