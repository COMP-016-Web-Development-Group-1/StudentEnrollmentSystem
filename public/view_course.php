<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

// Get and validate course id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid course ID.');
}

$courseId = $_GET['id'];

// Get course info
$courseStmt = $db->prepare('SELECT * FROM courses WHERE id = ?');
$courseStmt->execute([$courseId]);
$course = $courseStmt->fetch();

if (!$course) {
    die('Course not found.');
}

// Get students enrolled in this course
$stmt = $db->prepare('
    SELECT students.*
    FROM students
    WHERE students.course_id = ?
');
$stmt->execute([$courseId]);
$students = $stmt->fetchAll();

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

    <!-- Flowbite -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

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
    <title>College Name - <?= htmlspecialchars($course['course_name']) ?></title>
</head>

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>

    <div class="max-w-screen-2xl mx-auto px-4 py-6">
        <?php include base_path('public/partials/alert.php'); ?>

        <div class="">
            <div class="flex justify-between items-end mb-4">
                <div>
                    <h1 class="text-3xl font-bold"><?= htmlspecialchars($course['course_name']) ?></h1>
                    <p class="text-gray-500 text-sm">
                        <?= count($students) ?> student<?= count($students) === 1 ? '' : 's' ?> enrolled
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="courses.php"
                        class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-500 text-center transition flex items-center gap-1">
                        <i class="ti ti-arrow-left"></i>
                        Back to Courses
                    </a>
                    <a href="enroll.php?course_id=<?= urlencode($courseId) ?>"
                        class="bg-primary text-white px-2 py-1 rounded hover:bg-accent text-center transition flex items-center gap-1">
                        <i class="ti ti-user-plus"></i>
                        Enroll Student
                    </a>
                </div>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <table id="students-table" class="bg-white">
                    <thead>
                        <tr>
                            <th>
                                <span class="flex items-center gap-x-1">
                                    ID
                                    <i class="ti ti-caret-up-down-filled"></i>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center gap-x-1">
                                    Student Name
                                    <i class="ti ti-caret-up-down-filled"></i>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center gap-x-1">
                                    Email
                                    <i class="ti ti-caret-up-down-filled"></i>
                                </span>
                            </th>
                            <th>
                                <span class="flex items-center gap-x-1">
                                    Actions
                                    <i class="ti ti-caret-up-down-filled"></i>
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr class="hover:bg-gray-50 text-black">
                                <td><?= htmlspecialchars($student['id']) ?></td>
                                <td><?= htmlspecialchars($student['name']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td class="flex items-center gap-x-2">
                                    <a class="bg-green-600 px-2 py-1 text-white rounded hover:bg-green-500 transition"
                                        href="update_student.php?id=<?= $student['id'] ?>">Update</a>
                                    <a href="#"
                                        class="bg-red-600 px-2 py-1 text-white rounded hover:bg-red-500 transition open-unenroll-modal"
                                        data-id="<?= $student['id'] ?>">Unenroll</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-gray-400 py-6">No students enrolled in this course.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Unenroll Confirmation Modal -->
    <div id="unenroll-confirmation-modal" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg shadow p-6 w-full max-w-sm">
            <h2 class="text-lg font-semibold mb-2">Confirm Unenroll</h2>
            <p class="mb-4 text-sm text-gray-600">Are you sure you want to unenroll this student from the course?</p>
            <div class="flex justify-end gap-2">
                <button id="cancelUnenrollBtn"
                    class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Cancel</button>
                <a href="#" id="confirmUnenrollBtn"
                    class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded">Unenroll</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <script>
        if (document.getElementById("students-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#students-table", {
                searchable: true,
                sortable: true
            });
        }

        const modal = document.getElementById('unenroll-confirmation-modal');
        const confirmUnenrollBtn = document.getElementById('confirmUnenrollBtn');
        const cancelUnenrollBtn = document.getElementById('cancelUnenrollBtn');
        const unenrollLinks = document.querySelectorAll('.open-unenroll-modal');

        unenrollLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const studentId = this.getAttribute('data-id');
                confirmUnenrollBtn.href = `unenroll_student.php?id=${studentId}&course_id=<?= urlencode($courseId) ?>`;
                modal.classList.remove('hidden');
            });
        });

        cancelUnenrollBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            confirmUnenrollBtn.href = '#';
        });
    </script>
</body>

</html>