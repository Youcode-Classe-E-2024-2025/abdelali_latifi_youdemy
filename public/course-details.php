<?php
require_once '../backend/student.php';

$course_id = isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0 ? (int)$_GET['id'] : 0;

if ($course_id === 0) {
    die("Invalid course ID.");
}

$visitor = new Etudiant();  

if (isset($_GET['enroll']) && isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];  
    $enrolled = $visitor->enrollCourse($course_id, $student_id);  

    if ($enrolled) {
        $message = "You have successfully enrolled in the course!";
    } else {
        $message = "There was an error enrolling in the course. Please try again.";
    }
} else {
    $message = "";
}

try {
    $courseDetails = $visitor->getCourseDetails($course_id);

    if (!$courseDetails) {
        die("Course not found for ID: " . htmlspecialchars($course_id));
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
    <title>Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto px-6 py-10 bg-white shadow-lg rounded-lg mt-16">
        <h1 class="text-4xl font-bold text-blue-600 text-center"><?= htmlspecialchars($courseDetails['title']) ?></h1>
        <p class="text-center text-gray-500 mt-2">
            <strong>Category:</strong> <?= htmlspecialchars($courseDetails['category_name'] ?? 'Uncategorized') ?>
        </p>
        <div class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-800">Description</h2>
            <p class="mt-2 text-gray-700 leading-relaxed">
                <?= nl2br(htmlspecialchars($courseDetails['description'])) ?>
            </p>
        </div>
        <div class="mt-6">
            <h2 class="text-2xl font-semibold text-gray-800">Course Content</h2>
            <p class="mt-2 text-gray-700 leading-relaxed">
                <?= nl2br(htmlspecialchars($courseDetails['content'])) ?>
            </p>
        </div>
        <div class="text-center mt-10">
            <a href="dashbord-student.php" class="bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                Back to Courses
            </a>
        </div>

        <!-- Confirmation message -->
        <?php if ($message): ?>
            <div class="text-center mt-6 text-green-600 font-semibold"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="text-center mt-6">
            <a href="course-details.php?id=<?= $course_id ?>&enroll=true" class="bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400">
                Enroll in this Course
            </a>
        </div>
    </div>
</body>
</html>
