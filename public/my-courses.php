<?php
require_once '../backend/student.php';

session_start();

if (!isset($_SESSION['student_id'])) {
    die("Please log in first.");
}

$student_id = $_SESSION['student_id'];
$visitor = new Etudiant();

try {
    $myCourses = $visitor->getMyCourses($student_id);

    if (empty($myCourses)) {
        $message = "You have not enrolled in any courses yet.";
    } else {
        $message = "";
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
        <h1 class="text-4xl font-bold text-blue-600 text-center">My Enrolled Courses</h1>

        <?php if ($message): ?>
            <div class="text-center mt-6 text-gray-600 font-semibold"><?= htmlspecialchars($message) ?></div>
        <?php else: ?>
            <div class="mt-6 space-y-6">
                <?php foreach ($myCourses as $course): ?>
                    <div class="course-item p-6 bg-gray-50 rounded-md shadow-md">
                        <h2 class="text-2xl font-semibold text-gray-800"><?= htmlspecialchars($course['title']) ?></h2>
                        <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                        <?php if (!empty($course['category_name'])): ?>
                            <p class="mt-2 text-gray-500"><strong>Category:</strong> <?= htmlspecialchars($course['category_name']) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="text-center mt-6">
            <a href="dashbord-student.php" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
