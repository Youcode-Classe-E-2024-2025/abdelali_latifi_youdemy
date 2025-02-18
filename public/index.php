<?php
require_once '../backend/student.php';
require_once '../backend/courses.php';

$page = new Etudiant();
$courseManager = new Course();

$perPage = 3;

$pageNum = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$searchKeyword = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';

try {
    if ($searchKeyword) {
        $courses = $courseManager->searchCoursesPaginated($searchKeyword, $pageNum, $perPage);
        $totalCourses = $courseManager->getTotalCourses($searchKeyword);
    } else {
        $courses = $courseManager->getCoursesPaginated($pageNum, $perPage);
        $totalCourses = $courseManager->getTotalCourses();
    }

    $totalPages = ceil($totalCourses / $perPage);
} catch(Exception $e) {
    die("Erreur lors de la récupération des cours : " . $e->getMessage());
}
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
                <a href="#" class="text-xl font-bold text-blue-600 hover:text-blue-800">Youdemy</a>
                <div class="flex items-center space-x-4">
                    <button onclick="toggleModal('loginModal')" class="text-gray-700 hover:text-blue-600">Log In</button>
                    <button onclick="toggleModal('registerModal')" class="text-white bg-blue-600 px-4 py-2 rounded-md hover:bg-blue-700">Sign Up</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold mb-4">Discover the Joy of Learning</h1>
            <p class="text-lg font-medium mb-6">Explore hundreds of high-quality courses created by passionate instructors.</p>
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
                                <a onclick="toggleModal('loginModal')" class="text-blue-600 font-medium hover:underline">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600">No courses found for "<strong><?= htmlspecialchars($searchKeyword) ?></strong>".</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center">
                <nav aria-label="Pagination">
                    <ul class="flex space-x-2">
                        <!-- Lien vers la page précédente -->
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
    </div>
</main>

    <!-- Login Modal -->
<div id="loginModal" class="hidden flex fixed inset-0 bg-gray-900 bg-opacity-50 justify-center items-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">
        <div class="px-6 py-4">
            <h3 class="text-xl font-bold text-gray-800">Log In</h3>
            <?php if (!empty($_GET['error'])): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded-md mt-4">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>
            <form action="../backend/athentification/login.php" method="POST" class="mt-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        
                    >
                </div>
                <div class="mt-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        
                    >
                </div>
                <div class="mt-6">
                    <button 
                        type="submit" 
                        name="submit" 
                        class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">
                        Log In
                    </button>
                </div>
            </form>
        </div>
        <button onclick="toggleModal('loginModal')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
    </div>
</div>
    <!-- Register Modal -->
    <div id="registerModal" class="hidden flex fixed inset-0 bg-gray-900 bg-opacity-50 justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative">
            <div class="px-6 py-4">
                <h3 class="text-xl font-bold text-gray-800">Sign Up</h3>
                <?php if (!empty($message)) : ?>
                    <div class="mt-4 p-4 rounded-md text-white">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                <form action="../backend/athentification/signup.php" method="POST" class="mt-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" name="name" id="name"  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
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

    <script src="../js/visitor.js"></script>
</body>
</html>
