<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Teacher') {
    header('Location: login.php');
    exit;
}

$teacher_id = $_SESSION['student_id'];

require_once '../backend/teacher.php';
$courseManager = new Enseignant();

$message = '';
$error = '';

if (!isset($_GET['id'])) {
    header('Location: dashboard-teacher.php');
    exit;
}

$course_id = (int)$_GET['id'];

try {
    $course = $courseManager->getCourseById($course_id, $teacher_id);
    if (!$course) {
        $error = "Course not found or you don't have access to it.";
    }
} catch (Exception $e) {
    $error = "Error fetching course: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars(trim($_POST['title']));
    $description = htmlspecialchars(trim($_POST['description']));
    $content = htmlspecialchars(trim($_POST['content']));
    $tags = htmlspecialchars(trim($_POST['tags']));
    $category_id = (int)$_POST['category'];

    if (strlen($title) > 255) {
        $error = "The title cannot exceed 255 characters.";
    } else {
        try {
            $courseManager->updateCourse($course_id, $title, $description, $content, $tags, $category_id, $teacher_id);
            $message = "Course updated successfully!";

            $course = $courseManager->getCourseById($course_id, $teacher_id);
        } catch (Exception $e) {
            $error = "Error updating course: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Course</h1>

        <?php if ($message): ?>
            <div class="text-green-500 mb-4"><?= $message ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="text-red-500 mb-4"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($course): ?>
            <form method="POST">
                <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>" placeholder="Course Title" class="w-full px-4 py-2 mb-4 border rounded" required>
                <textarea name="description" placeholder="Course Description" class="w-full px-4 py-2 mb-4 border rounded" required><?= htmlspecialchars($course['description']) ?></textarea>
                <textarea name="content" placeholder="Course Content" class="w-full px-4 py-2 mb-4 border rounded" required><?= htmlspecialchars($course['content']) ?></textarea>
                <input type="text" name="tags" value="<?= htmlspecialchars($course['tags']) ?>" placeholder="Tags (comma-separated)" class="w-full px-4 py-2 mb-4 border rounded">
                <select name="category" class="w-full px-4 py-2 mb-4 border rounded" required>
                    <option value="">Select Category</option>
                    <option value="1" <?= $course['category_id'] == 1 ? 'selected' : '' ?>>Programming</option>
                    <option value="2" <?= $course['category_id'] == 2 ? 'selected' : '' ?>>Mathematics</option>
                    <option value="3" <?= $course['category_id'] == 3 ? 'selected' : '' ?>>Science</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Update Course</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
