<?php

require_once '../database/connexion.php';

class User extends Database {
    protected $db;

    public function __construct() {
        $this->db = $this->getConnection();
    }

    // Récupérer tous les cours
    public function getCourses() {
        $query = "SELECT course_id, title, description FROM courses";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les détails d'un cours 
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

    // rechercher un cours par mot-clé
    public function searchCourses($keyword) {
        $query = "SELECT * FROM courses WHERE title LIKE :keyword OR description LIKE :keyword";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', '%'.$keyword.'%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
