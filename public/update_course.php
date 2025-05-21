<?php
session_start();
require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

// Get course info (for both GET and POST)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid course ID.');
}

$courseId = $_GET['id'];
$stmt = $db->prepare('SELECT * FROM courses WHERE id = :id');
$stmt->execute(['id' => $courseId]);
$course = $stmt->fetch();

if (!$course) {
    die('Course not found.');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);

    if (!$course_name) {
        $error = "Course name is required.";
    } elseif (strlen($course_name) < 3) {
        $error = "Course name must be at least 3 characters long.";
    } elseif (strlen($course_name) > 100) {
        $error = "Course name must not exceed 100 characters.";
    } else {
        // Ensure new name is unique (excluding current course)
        $stmt = $db->prepare('SELECT COUNT(*) FROM courses WHERE course_name = :course_name AND id != :id');
        $stmt->execute(['course_name' => $course_name, 'id' => $courseId]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Course name already exists.";
        }
    }

    if (empty($error)) {
        $stmt = $db->prepare('UPDATE courses SET course_name = :course_name WHERE id = :id');
        $stmt->execute([
            'course_name' => $course_name,
            'id' => $courseId,
        ]);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Course updated successfully.',
        ];
        header('Location: courses.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- Tailwind CSS v4 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Themes -->
    <style type="text/tailwindcss">
        @theme {
            --color-background: #f8fbfb;
            --color-primary: #5ba6ac;
            --color-secondary: #9acdd1;
            --color-accent: #76c0c6;
            --font-manrope: "Manrope", "sans-serif";
        }
    </style>
    <title>College Name - Update Course</title>
</head>

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>
    <div class="max-w-screen-2xl mx-auto px-4 py-6">
        <form method="post" class="max-w-lg bg-white p-4 mx-auto">
            <h1 class="text-xl mb-4 font-bold text-center">Update Course Name</h1>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Course Name</label>
                <input type="text" name="course_name"
                    value="<?= htmlspecialchars($_POST['course_name'] ?? $course['course_name']) ?>" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-accent focus:border-accent">
                <?php if (!empty($error)): ?>
                    <p class="mt-1 text-sm text-red-500"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </div>
            <div class="mt-5 transition duration-300 flex items-center justify-end gap-x-4">
                <a href="courses.php"
                    class="bg-transparent cursor-pointer hover:underline focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary rounded">Cancel</a>
                <button type="submit"
                    class="text-white bg-primary cursor-pointer px-2 py-1 rounded focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</body>

</html>