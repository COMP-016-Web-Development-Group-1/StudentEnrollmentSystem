<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();
$stmt = $db->prepare('
    SELECT students.*, courses.course_name
    FROM students
    LEFT JOIN courses ON students.course_id = courses.id
');
$stmt->execute();
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
    <title>College Name - Students</title>
</head>

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>
    <?php include base_path('public/courses.php'); ?>
    <div class="max-w-screen-2xl mx-auto px-4 py-6">
        <!-- <h1 class="text-3xl">Students</h1> -->
        <?php include base_path('public/partials/alert.php'); ?>

        <div class="">
            <div class="flex justify-between items-end mb-4">
                <h1 class="text-3xl">Student List</h1>
                <a href="add_student.php"
                    class="bg-primary text-white px-2 py-1 rounded hover:bg-secondary text-center transition">
                    <i class="ti ti-plus"></i>
                    Add Student
                </a>
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
                                    Course
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
                                <td><?= htmlspecialchars($student['course_name'] ?? "No Course") ?></td>
                                <td class="flex items-center gap-x-2">
                                    <a class="bg-green-600 px-2 py-1 text-white rounded hover:bg-green-500 transition"
                                        href="update_student.php?id=<?= $student['id'] ?>">Update</a>
                                    <a class="bg-red-600 px-2 py-1 text-white rounded hover:bg-red-500 transition"
                                        href="delete_student.php?id=<?= $student['id'] ?>"
                                        onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
    </script>
</body>

</html>