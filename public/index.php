<?php
require_once '../backend/visitor.php';

$page = new Visitor();
$courses = $page->getCourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    </head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="text-xl font-semibold text-violet-600">Youdemy</a>
                </div>
                <div class="flex space-x-4 items-center">
                    <a href="login.html" class="text-gray-700 hover:text-violet-600">Log In</a>
                    <a href="register.html" class="text-white bg-violet-600 px-4 py-2 rounded hover:bg-violet-700">Sign Up</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Courses Section -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-700">Explore Our Courses</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mt-6">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="bg-white shadow rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($course['title']) ?></h3>
                            <p class="mt-2 text-sm text-gray-600"><?= htmlspecialchars($course['description']) ?></p>
                            <span class="text-sm text-gray-500 block mt-2">Category: <?= htmlspecialchars($course['category_name']) ?></span>
                            <a href="login.html" class="block mt-4 text-violet-600 font-medium hover:underline">View Details</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600">No courses available at the moment. Please check back later.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
