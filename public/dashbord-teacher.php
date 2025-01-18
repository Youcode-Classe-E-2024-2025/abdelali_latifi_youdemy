<?php
require_once '../backend/courses.php';
require_once '../backend/teacher.php';

$courseManager = new Teacher();

$teacher_id = 1; 

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
    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="#" class="text-xl font-bold text-blue-600 hover:text-blue-800">Youdemy</a>
                <div class="flex items-center space-x-4">
                    <a href="../logout.php" class="text-gray-700 hover:text-blue-600">Log Out</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-700 mb-6">Teacher Dashboard</h1>

            <!-- Add New Course -->
            <section class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4">Add New Course</h2>

                <?php if (isset($message)): ?>
                    <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">Content (URL or file link)</label>
                        <input type="text" name="content" id="content" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <input type="text" name="tags" id="tags"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select a category</option>
                            <!-- Categories should be dynamically fetched -->
                            <option value="1">Programming</option>
                            <option value="2">Design</option>
                        </select>
                    </div>

                    <div>
                        <button type="submit" name="add_course"
                            class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Add Course</button>
                    </div>
                </form>
            </section>

            <!-- Manage Courses -->
            <section class="bg-white shadow rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4">Manage Courses</h2>

                <?php if (!empty($courses)): ?>
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2">Title</th>
                                <th class="border border-gray-300 px-4 py-2">Category</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($course['title']) ?></td>
                                    <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($course['category_name']) ?></td>
                                    <td class="border border-gray-300 px-4 py-2">
                                        <a href="edit-course.php?id=<?= htmlspecialchars($course['course_id']) ?>" class="text-blue-600 hover:underline">Edit</a> |
                                        <a href="delete-course.php?id=<?= htmlspecialchars($course['course_id']) ?>" class="text-red-600 hover:underline">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No courses found.</p>
                <?php endif; ?>
            </section>

            <!-- Statistics -->
            <section class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Statistics</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-blue-100 p-4 rounded shadow">
                        <h3 class="text-lg font-bold">Total Courses</h3>
                        <p class="text-3xl font-bold text-blue-600"><?= htmlspecialchars($stats['total_courses']) ?></p>
                    </div>
                    <div class="bg-green-100 p-4 rounded shadow">
                        <h3 class="text-lg font-bold">Total Students</h3>
                        <p class="text-3xl font-bold text-green-600"><?= htmlspecialchars($stats['total_students']) ?></p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
