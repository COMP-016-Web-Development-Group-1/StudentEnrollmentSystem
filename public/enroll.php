<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

$errors = [];
$courseId = $_GET['course_id'] ?? null;

if (!$courseId || !is_numeric($courseId)) {
    die('Course not found.');
}

// Get course info
$courseStmt = $db->prepare("SELECT * FROM courses WHERE id = :id");
$courseStmt->execute(['id' => $courseId]);
$course = $courseStmt->fetch();

if (!$course) {
    die('Course not found.');
}

// Get students who are NOT yet enrolled in this course
$studentsStmt = $db->prepare("
    SELECT students.*, courses.course_name AS current_course
    FROM students
    LEFT JOIN courses ON students.course_id = courses.id
    WHERE students.course_id IS NULL OR students.course_id != :course_id
");
$studentsStmt->execute(['course_id' => $courseId]);
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);

$studentInfo = [];
foreach ($students as $student) {
    $studentInfo[$student['id']] = [
        'current_course' => $student['current_course'] ?? 'None'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['student_id'] ?? '';
    if (empty($studentId) || !is_numeric($studentId)) {
        $errors[] = "Please select a student.";
    } else {
        // Update student's course_id
        $update = $db->prepare("UPDATE students SET course_id = :course_id WHERE id = :student_id");
        $update->execute([
            'course_id' => $courseId,
            'student_id' => $studentId
        ]);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Student enrolled successfully.',
        ];
        header("Location: view_course.php?id=" . urlencode($courseId));
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
    <title>College Name - Enroll Student</title>
</head>

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>
    <div class="max-w-screen-2xl mx-auto px-4 py-6 border-secondary">
        <form method="POST" action="enroll.php?course_id=<?= urlencode($courseId) ?>"
            class="max-w-lg bg-white p-4 mx-auto">
            <h1 class="text-xl mb-4 font-bold text-center">
                Enroll a Student in <span class="text-primary"><?= htmlspecialchars($course['course_name']) ?></span>
            </h1>
            <?php if (!empty($errors)): ?>
                <div class="mb-4 text-red-600">
                    <?= htmlspecialchars(implode(', ', $errors)) ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="student_id" class="block text-sm font-medium mb-1">Select a Student</label>
                <select name="student_id" id="student_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-accent focus:border-accent"
                    required onchange="updateCurrentCourse()">
                    <option value="">-- Select a Student --</option>
                    <?php if ($students): ?>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= $student['id']; ?>"><?= htmlspecialchars($student['name']) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option disabled>No students available to enroll</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="current_course" class="block text-sm font-medium mb-1">Current Course</label>
                <input type="text" id="current_course"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm bg-gray-100" value="None"
                    readonly />
            </div>

            <div class="transition duration-300 flex items-center justify-end gap-x-4">
                <a href="view_course.php?id=<?= urlencode($courseId) ?>"
                    class="bg-transparent cursor-pointer hover:underline focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary rounded">Back</a>
                <button type="submit"
                    class="text-white bg-primary cursor-pointer px-2 py-1 rounded focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary">Enroll
                    Student</button>
            </div>
        </form>
    </div>
    <script>
        // Student info mapping from PHP to JS
        const studentInfo = <?= json_encode($studentInfo) ?>;
        function updateCurrentCourse() {
            const sel = document.getElementById('student_id');
            const currentCourseBox = document.getElementById('current_course');
            const selectedId = sel.value;
            if (selectedId && studentInfo[selectedId]) {
                currentCourseBox.value = studentInfo[selectedId].current_course || 'None';
            } else {
                currentCourseBox.value = 'None';
            }
        }
    </script>
</body>

</html>