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
    
    public function getCoursesPaginated($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $query = "SELECT course_id, title, description, categories.name AS category_name FROM courses INNER JOIN categories ON courses.category_id = categories.category_id LIMIT :offset, :perPage";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchCoursesPaginated($keyword, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT course_id, title, description, categories.name AS category_name FROM courses INNER JOIN categories ON courses.category_id = categories.category_id WHERE courses.title LIKE :keyword LIMIT :offset, :perPage";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCourses($keyword = null) {
        $query = "SELECT COUNT(*) AS total FROM courses";
        
        if ($keyword) {
            $query .= " WHERE title LIKE :keyword";
        }
    
        $stmt = $this->db->prepare($query);
        if ($keyword) {
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        }
        $stmt->execute();
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    

}
?>
