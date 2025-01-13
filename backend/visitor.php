<?php
require_once '../backend/courses.php';

class Visitor {
    private $courses;

    public function __construct() {
        try {
            $courseModel = new Course();
            $this->courses = $courseModel->getAllCourses();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
            $this->courses = [];
        }
    }

    public function getCourses() {
        return $this->courses;
    }
}
