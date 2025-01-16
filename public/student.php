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
    <title>Student Dashboard - Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="#" class="text-xl font-bold text-blue-600 hover:text-blue-800">Student Dashboard</a>
                <div>
                    <button onclick="window.location.href='../logout.php'" class="text-white bg-red-600 px-4 py-2 rounded-md hover:bg-red-700">
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-10">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold">Welcome to Your Dashboard</h1>
            <p class="text-lg font-medium mt-2">Explore and learn with the best courses tailored for you.</p>
        </div>
    </header>
    <!-- Courses Section -->
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
                                    <button onclick="toggleModal('loginModal')" class="text-blue-600 font-medium hover:underline">
                                        View Details
                                    </button>
                                </div>
                            </div>
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
