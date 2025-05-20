<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

$name = '';
$errors = [];
$course = $_GET['current_coursename'] ?? null;

if(!$course) {
    die('Course name not found.');
}
$findcourse = $db->prepare("SELECT course_id FROM courses WHERE course_name = :course");
$findcourse->execute(['course' => $course]);
$courseRow = $findcourse->fetch();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['name'])) {
        $errors[] = "Please select a student.";
    } else {
        $studentName = $_POST['name'];

        // Update student's course_id
        $update = $db->prepare("UPDATE students SET course_id = :course_id WHERE student_name = :studentName");
        $update->execute([
            'course_id' => $courseId,
            'student_name' => $studentName
        ]);

        // Optional: Redirect or display success
        header("Location: students.php?enrolled=1");
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

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>
    <div class="max-w-screen-2xl mx-auto px-4 py-6 border-secondary">
        <form method="POST" action="enroll_student.php" class="max-w-lg bg-white p-4 mx-auto">
            <h1 class="text-xl mb-4 font-bold text-center">Enroll a Student</h1>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium  mb-1">Select a Student</label>
                <select
                    name="name"
                    id="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-accent focus:border-accent"
                    required
                >
                    <option value="">-- Select a Student --</option>
                    <?php
                        if ($result && $result->rowCount() > 0) {
                            foreach ($result as $row) {
                                $studentName = htmlspecialchars($row['name']);
                                echo "<option value=\"$studentName\">$studentName</option>";
                            }
                        } else {
                            echo "<option disabled>No names found</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="transition duration-300 flex items-center justify-end gap-x-4">
                <a href="students.php"
                    class="bg-transparent cursor-pointer hover:underline focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary rounded">Back</a>
                <button type="submit"
                    class="text-white bg-primary cursor-pointer px-2 py-1 rounded focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary">Enroll
                    Student</button>
            </div>
        </form>
</body>

</html>








