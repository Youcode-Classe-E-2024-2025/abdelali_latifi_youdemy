<?php

require_once '../backend/user.php';

class Visiteur extends User {

    public function __construct() {
        parent::__construct();
    }

    // pagination
    public function getCoursesPaginated($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT course_id, title, description FROM courses LIMIT :offset, :perPage";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Créer un compte
    public function createAccount($name, $email, $password, $role) {
        if ($role != 'Student' && $role != 'Teacher') {
            return 'Rôle non valide';
        }
        $registration = new registre();
        return $registration->registration($name, $email, $password, $role);
    }
}
?>
