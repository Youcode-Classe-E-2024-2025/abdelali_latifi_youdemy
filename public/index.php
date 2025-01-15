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
    <title>Youdemy - Explore Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="#" class="text-xl font-bold text-blue-600 hover:text-blue-800">
                    Youdemy
                </a>
                <div class="flex items-center space-x-4">
                    <button onclick="toggleModal('loginModal')" class="text-gray-700 hover:text-blue-600">Log In</button>
                    <button onclick="toggleModal('registerModal')" class="text-white bg-blue-600 px-4 py-2 rounded-md hover:bg-blue-700">
                        Sign Up
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold mb-4">Discover the Joy of Learning</h1>
            <p class="text-lg font-medium mb-6">Explore hundreds of high-quality courses created by passionate instructors.</p>
            <button onclick="toggleModal('registerModal')" class="bg-white text-blue-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100">
                Get Started for Free
            </button>
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
    <!-- Login Modal -->
    <div id="loginModal" class="hidden flex fixed inset-0 bg-gray-900 bg-opacity-50 justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">
            <div class="px-6 py-4">
                <h3 class="text-xl font-bold text-gray-800">Log In</h3>
                <form action="../backend/athentification/login.php" method="POST" class="mt-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mt-6">
                        <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Log In</button>
                    </div>
                </form>
            </div>
            <button onclick="toggleModal('loginModal')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
        </div>
    </div>
    <!-- Register Modal -->
    <div id="registerModal" class="hidden flex fixed inset-0 bg-gray-900 bg-opacity-50 justify-center items-center z-50">
    <?php if (!empty($message)): ?>
            <div class="w-full p-3 mb-4 text-white rounded-lg <?php echo $result == 1 ? 'bg-green-500' : 'bg-red-500'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
    <?php endif; ?>
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">
        <div class="px-6 py-4">
            <h3 class="text-xl font-bold text-gray-800">Sign Up</h3>
            <form action="../backend/athentification/signup.php" method="POST" class="mt-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mt-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mt-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <select name="role" id="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
                    </select>
                </div>
                <div class="mt-6">
                    <button type="submit" name="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Sign Up</button>
                </div>
            </form>
        </div>
        <button onclick="toggleModal('registerModal')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
    </div>
</div>
<script src="../js/visitor.js" ></script>
</body>
</html>
