<?php
require_once '../backend/student.php'; 
require_once '../backend/courses.php';

session_start();
!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student' ? header(header: 'Location: index.php') :'';

$page = new Etudiant();
$courseManeger = new Course();

$perPage = 3;

$pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$searchKeyword = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '' ;

try {
    if ($searchKeyword) {
        $courses = $courseManeger->searchCoursesPaginated($searchKeyword, $pageNum, $perPage);
        $totalCourses = $courseManeger->getTotalCourses($searchKeyword);
    } else {
        $courses = $courseManeger->getCoursesPaginated($pageNum, $perPage);
        $totalCourses = $courseManeger->getTotalCourses();
    }

    $totalPages = ceil($totalCourses / $perPage);
} catch(Exception $e) {
    die ("Erreur lors de la récupération des cours : " . $e->getMessage());
}
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
            <h1 class="text-4xl font-extrabold mb-4">Welcome to Your Dashboard</h1>
            <p class="text-lg font-medium mb-6">Explore and learn with the best courses tailored for you.</p>
            <form method="GET" class="mt-6 flex text-black justify-center">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search courses by keyword..." 
                    value="<?= htmlspecialchars($searchKeyword) ?>" 
                    class="px-4 py-2 rounded-l-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 w-80"
                />
                <button 
                    type="submit" 
                    class="bg-blue-600 px-4 py-2 rounded-r-md hover:bg-blue-700">
                    Search
                </button>
            </form>
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
                                    <a href="course-details.php?id=<?= htmlspecialchars($course['course_id']) ?>" class="text-blue-600 font-medium hover:underline">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600">No courses available at the moment. Please check back later.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center">
                <nav aria-label="Pagination">
                    <ul class="flex space-x-2">
                        <?php if ($pageNum > 1): ?>
                            <li>
                                <a href="?page=<?= $pageNum - 1 ?>&search=<?= urlencode($searchKeyword) ?>" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                    &laquo; Previous
                                </a>
                            </li>
                        <?php endif; ?>
                        <!-- les pages suivantes -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li>
                                <a href="?page=<?= $i ?>&search=<?= urlencode($searchKeyword) ?>" class="px-4 py-2 text-white <?= $i == $pageNum ? 'bg-blue-700' : 'bg-blue-600' ?> hover:bg-blue-700 rounded-md">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <!--la page suivante -->
                        <?php if ($pageNum < $totalPages): ?>
                            <li>
                                <a href="?page=<?= $pageNum + 1 ?>&search=<?= urlencode($searchKeyword) ?>" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                    Next &raquo;
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
