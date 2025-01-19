<?php
session_start();  
require_once '../backend/student.php';  


if (!isset($_SESSION['student_id'])) {
    die("Please log in first.");
}

$student_id = $_SESSION['student_id']; 
$student = new Etudiant();

$courses = $student->getMyCourses($student_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold text-gray-700 mb-6">My Enrolled Courses</h1>

        <?php if (empty($courses)): ?>
            <p class="text-gray-600">You have not enrolled in any courses yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <?php foreach ($courses as $course): ?>
                    <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-800">
                                <?= htmlspecialchars($course['title']) ?>
                            </h3>
                            <p class="text-sm text-gray-600 mt-2">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>
                            <span class="text-sm text-gray-500 block mt-2">
                                Category: <?= htmlspecialchars($course['category_name']) ?>
                            </span>
                            <div class="mt-4">
                                <a href="detaille-course.php?id=<?= htmlspecialchars($course['course_id']) ?>" class="text-blue-600 font-medium hover:underline">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
