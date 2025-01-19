<?php

require_once '../backend/user.php';

class Enseignant {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO('mysql:host=localhost;dbname=Youdemy', 'root', ''); // Mettez vos informations de connexion ici
    }

    public function addCourse($title, $description, $content, $tags, $category_id, $teacher_id) {
        $sql = "INSERT INTO courses (title, description, content, category_id, teacher_id) 
                VALUES (:title, :description, :content, :category_id, :teacher_id)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':content' => $content,
            ':category_id' => $category_id,
            ':teacher_id' => $teacher_id
        ]);

        $course_id = $this->pdo->lastInsertId(); 

        if (!empty($tags)) {
            $tags = explode(',', $tags); 
            foreach ($tags as $tag) {
                $tag = trim($tag);
                $this->addTagIfNotExists($tag);
                $tag_id = $this->getTagId($tag);
                $this->associateTagWithCourse($course_id, $tag_id);
            }
        }

        return true;
    }

    private function addTagIfNotExists($tag) {
        $sql = "INSERT IGNORE INTO tags (name) VALUES (:name)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $tag]);
    }

    private function getTagId($tag) {
        $sql = "SELECT tag_id FROM tags WHERE name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':name' => $tag]);
        return $stmt->fetchColumn();
    }

    private function associateTagWithCourse($course_id, $tag_id) {
        $sql = "INSERT INTO course_tags (course_id, tag_id) VALUES (:course_id, :tag_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':course_id' => $course_id, ':tag_id' => $tag_id]);
    }

    //  les statistiques
    public function getCourseStatistics($teacher_id) {
        $sql = "SELECT COUNT(c.course_id) AS total_courses, 
                       COUNT(e.student_id) AS total_students
                FROM courses c
                LEFT JOIN enrollments e ON c.course_id = e.course_id
                WHERE c.teacher_id = :teacher_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':teacher_id' => $teacher_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer les cours d'un enseignant
    public function getCoursesByTeacher($teacher_id) {
        $sql = "SELECT c.course_id, c.title, c.description, cat.name AS category_name 
                FROM courses c
                JOIN categories cat ON c.category_id = cat.category_id
                WHERE c.teacher_id = :teacher_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':teacher_id' => $teacher_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
