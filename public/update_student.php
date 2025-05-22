<?php
session_start();
require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

// Get student info (for both GET and POST)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid student ID.');
}

$studentId = $_GET['id'];
$stmt = $db->prepare('SELECT * FROM students WHERE id = :id');
$stmt->execute(['id' => $studentId]);
$student = $stmt->fetch();

if (!$student) {
    die('Student not found.');
}

// Get all courses
$coursesStmt = $db->query('SELECT * FROM courses');
$courses = $coursesStmt->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $remove_course = isset($_POST['remove_course']);
    $course_id = $remove_course ? null : $student['course_id'];

    if (!$name || !$email) {
        $error = "Name and email are required.";
    } else {
        $stmt = $db->prepare('UPDATE students SET name = :name, email = :email, course_id = :course_id WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'course_id' => $course_id,
            'id' => $studentId,
        ]);

        $_SESSION['success'] = 'Student updated successfully.';
        header('Location: students.php');
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
    <title>College Name - Add Student</title>
</head>

<body class="bg-background min-h-screen flex flex-col font-manrope">
    <?php include base_path('public/partials/header.php'); ?>

    <main class="flex-grow">
    <div class="max-w-screen-2xl mx-auto px-4 py-6">
        <form method="post" class="max-w-lg bg-white p-4 mx-auto">
            <h1 class="text-xl mb-4 font-bold text-center">Update Student Information</h1>
            <div class="mb-4">
                <label class="block text-sm font-medium  mb-1">Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-accent focus:border-accent">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium  mb-1">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-primary focus:border-primary">
            </div>

            <div>
                <label class="block text-sm font-medium  mb-1">Current Course:</label>
                <input type="text" value="<?php
                $currentCourse = 'None';
                foreach ($courses as $course) {
                    if ($course['id'] == $student['course_id']) {
                        $currentCourse = $course['course_name'];
                        break;
                    }
                }
                echo htmlspecialchars($currentCourse);
                ?>" readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-primary focus:border-primary">
            </div>

            <?php if ($student['course_id']): ?>
                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" name="remove_course" id="remove_course"
                            class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                        <label for="remove_course" class="text-sm text-gray-700">Remove course assignment</label>
                    </div>
            <?php endif; ?>

            <div class=" mt-5 transition duration-300 flex items-center justify-end gap-x-4">
                <a href="students.php"
                    class="bg-transparent cursor-pointer hover:underline focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary rounded">Cancel</a>
                <button type="submit"
                    class="text-white bg-primary cursor-pointer px-2 py-1 rounded focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </main>
    <?php include base_path('public/partials/footer.php'); ?>

</body>

</html>
