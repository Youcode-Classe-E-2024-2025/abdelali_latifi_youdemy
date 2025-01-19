<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header('Location: login.php'); 
    exit;
}

$teacher_id = $_SESSION['student_id']; 
require_once '../backend/courses.php';
require_once '../backend/teacher.php';

$courseManager = new Enseignant();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $content = htmlspecialchars(trim($_POST['content']));
    $tags = htmlspecialchars(trim($_POST['tags']));
    $category = (int) $_POST['category'];

    try {
        $courseManager->addCourse($title, $description, $content, $tags, $category, $teacher_id);
        $message = "Course added successfully!";
    } catch (Exception $e) {
        $error = "Error adding course: " . $e->getMessage();
    }
}

try {
    $courses = $courseManager->getCoursesByTeacher($teacher_id); 
} catch (Exception $e) {
    $error = "Error fetching courses: " . $e->getMessage();
}

$stats = $courseManager->getCourseStatistics($teacher_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="#" class="text-xl font-bold text-blue-600 hover:text-blue-800">Teacher Dashboard</a>
                <div class="flex space-x-4">
                    <a href="my-courses.php" class="text-blue-600 hover:text-blue-800">My Courses</a>
                    <a href="../backend/athentification/logout.php">
                        <button class="text-white bg-red-600 px-4 py-2 rounded-md hover:bg-red-700">
                            Log Out
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold mb-4">Welcome to Your Teacher Dashboard</h1>
            <p class="text-lg font-medium mb-6">Manage your courses and track your students.</p>
        </div>
    </header>

    <!-- Courses Section -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-700 mb-6">My Courses</h2>

            <!-- Add New Course Form -->
            <div class="mb-8">
                <form method="POST">
                    <input type="text" name="title" placeholder="Course Title" class="px-4 py-2 rounded-md border border-gray-300 w-full mb-4" required>
                    <textarea name="description" placeholder="Course Description" class="px-4 py-2 rounded-md border border-gray-300 w-full mb-4" required></textarea>
                    <textarea name="content" placeholder="Course Content" class="px-4 py-2 rounded-md border border-gray-300 w-full mb-4" required></textarea>
                    <input type="text" name="tags" placeholder="Course Tags" class="px-4 py-2 rounded-md border border-gray-300 w-full mb-4">
                    <select name="category" class="px-4 py-2 rounded-md border border-gray-300 w-full mb-4" required>
                        <option value="">Select Category</option>
                        <option value="1">Programming</option>
                        <option value="2">Mathematics</option>
                        <option value="3">Science</option>
                    </select>
                    <button type="submit" name="add_course" class="bg-blue-600 text-white px-6 py-3 rounded-md">Add Course</button>
                </form>
                <?php if ($message): ?>
                    <div class="text-green-500 mt-4"><?= $message ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="text-red-500 mt-4"><?= $error ?></div>
                <?php endif; ?>
            </div>

            <!-- Display Courses -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($course['title']) ?></h3>
                                <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($course['description']) ?></p>
                                <span class="text-sm text-gray-500 block mt-2">Category: <?= htmlspecialchars($course['category_name']) ?></span>
                                <div class="mt-4">
                                    <a href="course-details.php?id=<?= htmlspecialchars($course['course_id']) ?>" class="text-blue-600 font-medium hover:underline">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600">You don't have any courses yet. Start by adding a new one!</p>
                <?php endif; ?>
            </div>

            <!-- Course Statistics -->
            <h2 class="text-3xl font-bold text-gray-700 mt-10 mb-6">Course Statistics</h2>
            <div class="bg-white shadow-lg rounded-lg p-6">
                <p><strong>Total Courses:</strong> <?= htmlspecialchars($stats['total_courses']) ?></p>
                <p><strong>Total Students Enrolled:</strong> <?= htmlspecialchars($stats['total_students']) ?></p>
            </div>
        </div>
    </main>
</body>
</html>
