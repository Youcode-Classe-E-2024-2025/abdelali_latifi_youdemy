<?php
require_once '../database/connexion.php';

class Course {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllCourses() {
        try {
            $query = "
                SELECT 
                    courses.course_id, 
                    courses.title, 
                    courses.description, 
                    categories.name AS category_name
                FROM courses
                INNER JOIN categories ON courses.category_id = categories.category_id
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des cours : " . $e->getMessage());
        }
    }

    public function searchCourses($keyword) {
        try {
            $query = "
                SELECT 
                    courses.course_id, 
                    courses.title, 
                    courses.description, 
                    categories.name AS category_name
                FROM courses
                INNER JOIN categories ON courses.category_id = categories.category_id
                WHERE courses.title LIKE :keyword OR courses.description LIKE :keyword
            ";

            $stmt = $this->db->prepare($query);
            $stmt->execute([':keyword' => '%' . $keyword . '%']);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la recherche des cours : " . $e->getMessage());
        }
    }
}
