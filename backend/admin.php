<?php

require_once '../backend/user.php';

class Admin extends User {

    public function __construct() {
        parent::__construct();
    }

    // Validation des comptes enseignants
    public function validateTeacher($teacher_id) {
        $sql = "UPDATE users SET is_active = TRUE WHERE user_id = :teacher_id AND role = 'Teacher';";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':teacher_id' => $teacher_id]);
        return $stmt->rowCount(); // Retourne le nombre de lignes affectées
    }

    // Activation, suspension ou suppression des utilisateurs
    public function updateUserStatus($user_id, $is_active) {
        $sql = "UPDATE users SET is_active = :is_active WHERE user_id = :user_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':is_active' => $is_active,
            ':user_id' => $user_id
        ]);
        return $stmt->rowCount();
    }

    public function deleteUser($user_id) {
        $sql = "DELETE FROM users WHERE user_id = :user_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->rowCount();
    }

    // Gestion des cours, catégories et tags
    public function deleteCourse($course_id) {
        // Supprimer les dépendances dans la table enrollments
        $sql = "DELETE FROM enrollments WHERE course_id = :course_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':course_id' => $course_id]);
    
        // Supprimer le cours
        $sql = "DELETE FROM courses WHERE course_id = :course_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':course_id' => $course_id]);
    
        return $stmt->rowCount();
    }
    
    public function addCategory($name) {
        $sql = "INSERT INTO categories (name) VALUES (:name);";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':name' => $name]);
        return $stmt->rowCount();
    }

    public function deleteCategory($category_id) {
        $sql = "DELETE FROM categories WHERE category_id = :category_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category_id' => $category_id]);
        return $stmt->rowCount();
    }

    public function bulkInsertTags($tags) {
        // Diviser la chaîne des tags en un tableau
        $tagsArray = explode(',', $tags);
        
        // Supprimer les espaces inutiles autour de chaque tag
        $tagsArray = array_map('trim', $tagsArray);
    
        // Insérer chaque tag dans la base de données
        $sql = "INSERT INTO tags (name) VALUES (:name) ON DUPLICATE KEY UPDATE name = name;";
        $stmt = $this->db->prepare($sql);
    
        foreach ($tagsArray as $tag) {
            $stmt->execute([':name' => $tag]);
        }
    
        return true;
    }
    

    // Statistiques globales
    public function getGlobalStatistics() {
        $stats = [];

        // Nombre total de cours
        $sql = "SELECT COUNT(*) AS total_courses FROM courses;";
        $stats['total_courses'] = $this->db->query($sql)->fetchColumn();

        // Répartition des cours par catégorie
        $sql = "SELECT cat.name AS category, COUNT(c.course_id) AS course_count
                FROM categories cat
                LEFT JOIN courses c ON cat.category_id = c.category_id
                GROUP BY cat.category_id;";
        $stats['courses_by_category'] = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        // Cours avec le plus d'étudiants
        $sql = "SELECT c.title, COUNT(e.student_id) AS student_count
                FROM courses c
                JOIN enrollments e ON c.course_id = e.course_id
                GROUP BY c.course_id
                ORDER BY student_count DESC
                LIMIT 1;";
        $stats['most_popular_course'] = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

        // Top 3 enseignants
        $sql = "SELECT u.name, COUNT(c.course_id) AS course_count
                FROM users u
                JOIN courses c ON u.user_id = c.teacher_id
                WHERE u.role = 'Teacher'
                GROUP BY u.user_id
                ORDER BY course_count DESC
                LIMIT 3;";
        $stats['top_teachers'] = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    public function getCategories() {
        $sql = "SELECT category_id, name FROM categories";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTags() {
        $sql = "SELECT tag_id, name FROM tags";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Méthode pour supprimer un tag
    public function deleteTag($tag_id) {
        $sql = "DELETE FROM tags WHERE tag_id = :tag_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tag_id' => $tag_id]);
        return $stmt->rowCount(); // Retourne le nombre de lignes affectées
    }

    public function getTeachers() {
        $query = "SELECT user_id, name, email, is_active FROM users WHERE role = 'Teacher'";
        $stmt = $this->db->prepare($query); // Suppose que $this->db est votre connexion PDO
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}

?>
