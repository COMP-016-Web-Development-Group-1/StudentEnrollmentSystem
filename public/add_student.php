<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

$name = '';
$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($name)) {
        $errors['name'] = 'Name is required';
    } elseif (strlen($name) < 3) {
        $errors['name'] = 'Name must be at least 3 characters long';
    } elseif (strlen($name) > 50) {
        $errors['name'] = 'Name must not exceed 50 characters';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    } elseif (strlen($email) > 100) {
        $errors['email'] = 'Email must not exceed 100 characters';
    } else {
        // Check if email already exists
        $stmt = $db->prepare('SELECT COUNT(*) FROM students WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $errors['email'] = 'Email already exists';
        }
    }

    if (empty($errors)) {
        $stmt = $db->prepare('INSERT INTO students (name, email) VALUES (?, ?)');
        $stmt->execute([$name, $email]);

        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Student added successfully',
        ];
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

<body class="bg-background min-h-screen font-manrope">
    <?php include base_path('public/partials/header.php'); ?>
    <div class="max-w-screen-2xl mx-auto px-4 py-6 border-secondary">
        <form method="POST" action="add_student.php" class="max-w-lg bg-white p-4 mx-auto">
            <h1 class="text-xl mb-4 font-bold text-center">Add Student</h1>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium  mb-1">Name</label>
                <input type="text" name="name" id="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-accent focus:border-accent"
                    value="<?= htmlspecialchars($name) ?>" required />
                <?php if (isset($errors['name'])): ?>
                    <p class=" mt-1 text-sm text-red-500"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium  mb-1">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring focus:ring-primary focus:border-primary"
                    value="<?= htmlspecialchars($email) ?>" required />
                <?php if (isset($errors['email'])): ?>
                    <p class=" mt-1 text-sm text-red-500"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>


            <div class="transition duration-300 flex items-center justify-end gap-x-4">
                <a href="students.php"
                    class="bg-transparent cursor-pointer hover:underline focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary rounded">Back</a>
                <button type="submit"
                    class="text-white bg-primary cursor-pointer px-2 py-1 rounded focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-primary focus:border-primary">Add
                    Student</button>
            </div>
        </form>
</body>

</html>