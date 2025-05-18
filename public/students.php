<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();
$stmt = $db->prepare('SELECT * FROM students');
$stmt->execute();
$studentData = $stmt->fetchAll();

// dd($studentData);
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
    <title>College Name - Students</title>
</head>

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>
    <?php include base_path('public/courses.php'); ?>
    <div class="max-w-screen-2xl mx-auto border-secondary">
        <h1 class="text-3xl">Students</h1>
        <?php include base_path('public/partials/alert.php'); ?>

        <a href="add_student.php" class="bg-secondary rounded-sm gap-x-2 inline-flex p-2 items-center">
            <i class="ti ti-plus text-xl"></i>
            Add
            Student
        </a>

        <?= dd($studentData) ?>
    </div>
</body>

</html>