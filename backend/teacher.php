<?php

require_once '../backend/user.php';

class Enseignant extends User {

    public function __construct() {
        parent::__construct();
    }

    // Ajouter un nouveau cours
    public function addCourse($title, $description, $content, $tags, $category_id, $teacher_id) {
        try {
            $query = "
                INSERT INTO courses (title, description, content, tags, category_id, teacher_id)
                VALUES (:title, :description, :content, :tags, :category_id, :teacher_id)
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':tags' => $tags,
                ':category_id' => $category_id,
                ':teacher_id' => $teacher_id
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Mettre à jour un cours
    public function updateCourse($course_id, $title, $description, $content, $tags, $category_id) {
        try {
            $query = "
                UPDATE courses
                SET title = :title, description = :description, content = :content, tags = :tags, category_id = :category_id
                WHERE course_id = :course_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':course_id' => $course_id,
                ':title' => $title,
                ':description' => $description,
                ':content' => $content,
                ':tags' => $tags,
                ':category_id' => $category_id
            ]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Supprimer un cours
    public function deleteCourse($course_id) {
        try {
            $query = "DELETE FROM courses WHERE course_id = :course_id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':course_id' => $course_id]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCourseStatistics($teacher_id) {
        try {
            $query = "
                SELECT 
                    (SELECT COUNT(*) FROM courses WHERE teacher_id = :teacher_id) AS total_courses,
                    (SELECT COUNT(*) FROM enrollments 
                     INNER JOIN courses ON enrollments.course_id = courses.course_id
                     WHERE courses.teacher_id = :teacher_id) AS total_students
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':teacher_id' => $teacher_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getCoursesByTeacher($teacher_id) {
        try {
            $query = "
                SELECT courses.course_id, courses.title, courses.description, categories.category_name
                FROM courses
                INNER JOIN categories ON courses.category_id = categories.category_id
                WHERE courses.teacher_id = :teacher_id
            ";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':teacher_id' => $teacher_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retourner les cours sous forme de tableau associatif
        } catch (Exception $e) {
            return false;  
        }
    }
}
?>
