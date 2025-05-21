<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();
$stmt = $db->prepare('SELECT * FROM courses');
$stmt->execute();
$courses = $stmt->fetchAll();
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
    <title>College Name - Courses</title>
</head>

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <?php include base_path('public/partials/alert.php'); ?>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
            <h1 class="text-3xl font-bold text-primary">Courses</h1>
            <a href="add_course.php"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-accent transition focus:outline-none focus:ring-2 focus:ring-accent">
                <i class="ti ti-plus"></i> Add Course
            </a>
        </div>

        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition flex flex-col relative">
                    <div class="absolute top-4 right-4 z-10">
                        <button type="button" id="dropdownMenuIconButton-<?= $course['id'] ?>"
                            data-dropdown-toggle="dropdownDots-<?= $course['id'] ?>"
                            class="inline-flex items-center p-2 text-sm font-medium text-gray-500 bg-transparent rounded-lg hover:bg-gray-100 focus:outline-none">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div id="dropdownDots-<?= $course['id'] ?>"
                            class="hidden z-20 bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                            <ul class="py-2 text-sm text-gray-700"
                                aria-labelledby="dropdownMenuIconButton-<?= $course['id'] ?>">
                                <li>
                                    <a href="update_course.php?id=<?= urlencode($course['id']) ?>"
                                        class="block px-4 py-2 hover:bg-gray-100 flex items-center gap-2">
                                        <i class="ti ti-edit"></i> Update Course
                                    </a>
                                </li>
                                <li>
                                    <button type="button"
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center gap-2 text-red-600 open-delete-modal"
                                        data-id="<?= $course['id'] ?>">
                                        <i class="ti ti-trash"></i> Delete Course
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex-1 p-6 flex flex-col">
                        <h2 class="text-xl font-extrabold text-gray-800 mb-2">
                            <?= htmlspecialchars($course['course_name']) ?>
                        </h2>
                        <?php if (!empty($course['description'])): ?>
                            <p class="text-gray-600 mb-3"><?= htmlspecialchars($course['description']) ?></p>
                        <?php endif; ?>
                        <hr class="my-2 border-t border-gray-400 opacity-50">
                        <div class="mt-4 flex justify-center">
                            <a href="view_course.php?id=<?= urlencode($course['id']) ?>"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-medium hover:bg-accent transition focus:outline-none focus:ring-2 focus:ring-accent">
                                <i class="ti ti-eye"></i> View Course
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center"
        style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-lg shadow p-6 w-full max-w-sm">
            <h2 class="text-lg font-semibold mb-2">Confirm Deletion</h2>
            <p class="mb-4 text-sm text-gray-600">Are you sure you want to delete this course?</p>
            <div class="flex justify-end gap-2">
                <button id="cancelDeleteBtn"
                    class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Cancel</button>
                <a href="#" id="confirmDeleteBtn"
                    class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded">Delete</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script>
        const modal = document.getElementById('delete-confirmation-modal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const deleteLinks = document.querySelectorAll('.open-delete-modal');

        deleteLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const courseId = this.getAttribute('data-id');
                confirmDeleteBtn.href = `delete_course.php?id=${courseId}`;
                modal.classList.remove('hidden');
            });
        });

        cancelDeleteBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            confirmDeleteBtn.href = '#';
        });
    </script>
</body>

</html>