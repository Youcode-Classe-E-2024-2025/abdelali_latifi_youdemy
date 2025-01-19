<?php
session_start();
require_once '../backend/student.php';

$student_id = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

if (!$student_id) {
    die("Please log in first.");
}

$visitor = new Etudiant();  

try {
    $courses = $visitor->getCoursesByStudent($student_id);

    if (!$courses) {
        $message = "You have not enrolled in any courses yet.";
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
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
    <div class="max-w-4xl mx-auto px-6 py-10 bg-white shadow-lg rounded-lg mt-16">
        <h1 class="text-4xl font-bold text-blue-600 text-center mb-8">My Enrolled Courses</h1>
        <?php if (isset($message)): ?>
            <div class="text-center text-lg text-red-600 font-semibold">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php else: ?>
            <!-- Afficher les cours -->
            <div class="space-y-8">
                <?php foreach ($courses as $course): ?>
                    <div class="bg-white shadow-lg rounded-lg p-6 flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold text-blue-600"><?= htmlspecialchars($course['title']) ?></h2>
                            <p class="mt-2 text-gray-500"><strong>Category:</strong> <?= htmlspecialchars($course['category_name']) ?></p>
                            <p class="mt-4 text-gray-700"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                        </div>
                        <div class="mt-4 md:mt-0 md:w-48 text-center">
                            <a href="course-details.php?id=<?= $course['course_id'] ?>" class="block bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-300">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <div class="text-center mt-10">
            <a href="dashbord-student.php" class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400">
                Back to Dashboard
            </a>
        </div>
    </div>

</body>
</html>
