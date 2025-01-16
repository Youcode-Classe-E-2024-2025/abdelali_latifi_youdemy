<?php
require_once '../backend/visitor.php';

// Récupération de l'ID depuis l'URL
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($course_id === 0) {
    die("Invalid course ID.");
}

$visitor = new Visitor();

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
    <div class="max-w-4xl mx-auto px-4 py-10 bg-white shadow-md rounded-lg">
        <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($courseDetails['title']) ?></h1>
        <p class="text-sm text-gray-600 mt-2">
            <strong>Category:</strong> <?= htmlspecialchars($courseDetails['category_name'] ?? 'Uncategorized') ?>
        </p>
        <p class="text-gray-700 mt-4">
            <?= nl2br(htmlspecialchars($courseDetails['description'])) ?>
        </p>
        <div class="mt-6">
            <h2 class="text-xl font-semibold text-gray-800">Course Content</h2>
            <p class="mt-2 text-gray-700"><?= nl2br(htmlspecialchars($courseDetails['content'])) ?></p>
        </div>
        <a href="student-dashboard.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            Back to Courses
        </a>
    </div>
</body>
</html>
