<?php

require_once '../backend/user.php';

class Admin extends User {

    public function __construct() {
        parent::__construct();
    }

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

    public function deleteCourse($course_id) {
        $sql = "DELETE FROM enrollments WHERE course_id = :course_id;";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':course_id' => $course_id]);
    
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
        $tagsArray = explode(',', $tags);
        
        $tagsArray = array_map('trim', $tagsArray);
    
        $sql = "INSERT INTO tags (name) VALUES (:name) ON DUPLICATE KEY UPDATE name = name;";
        $stmt = $this->db->prepare($sql);
    
        foreach ($tagsArray as $tag) {
            $stmt->execute([':name' => $tag]);
        }
    
        return true;
    }
    

    public function getGlobalStatistics() {
        $stats = [];

        $sql = "SELECT COUNT(*) AS total_courses FROM courses;";
        $stats['total_courses'] = $this->db->query($sql)->fetchColumn();

        $sql = "SELECT cat.name AS category, COUNT(c.course_id) AS course_count
                FROM categories cat
                LEFT JOIN courses c ON cat.category_id = c.category_id
                GROUP BY cat.category_id;";
        $stats['courses_by_category'] = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT c.title, COUNT(e.student_id) AS student_count
                FROM courses c
                JOIN enrollments e ON c.course_id = e.course_id
                GROUP BY c.course_id
                ORDER BY student_count DESC
                LIMIT 1;";
        $stats['most_popular_course'] = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

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

    public function deleteTag($tag_id) {
        $sql = "DELETE FROM tags WHERE tag_id = :tag_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':tag_id' => $tag_id]);
        return $stmt->rowCount(); 
    }

    public function getTeachers() {
        $query = "SELECT user_id, name, email, is_active FROM users WHERE role = 'Teacher'";
        $stmt = $this->db->prepare($query); 
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteTeacher($userId) {
        $query = "DELETE FROM users WHERE user_id = :user_id AND role = 'Teacher'";
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    
    
}

?>
