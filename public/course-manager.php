<?php
require_once '../backend/student.php';
require_once '../backend/courses.php';

$page = new Etudiant();
$courseManeger = new Course();

$searchKeyword = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '' ;

try {
    $courses = $searchKeyword ? $courseManeger->searchCourses($searchKeyword) : $courseManeger->getAllCourses();
} catch(Exception $e) {
    die("Erreur lors de la récupération des cours : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Explore Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">


    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-700 mb-6">Featured Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <?php if (!empty($courses)): ?>
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
                                    <a onclick="toggleModal('loginModal')"<?= htmlspecialchars($course['course_id']) ?> class="text-blue-600 font-medium hover:underline">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600">No courses found for "<strong><?= htmlspecialchars($searchKeyword) ?></strong>".</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
