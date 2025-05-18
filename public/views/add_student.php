<?php
session_start();

require_once '../../src/functions.php';
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
            'text' => 'Student added successfully',
            'type' => 'success'
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
            --color-clifford: #da373d;
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
    <div class="max-w-screen-2xl mx-auto px-4 py-6 border-secondary">
        <h1 class="text-3xl">Add Student</h1>
        <form method="POST" action="add_student.php">
            <div>
                <label for="name">Name: <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-500"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="email">Email: <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="transition duration-300">
                <button type="submit" class="bg-primary cursor-pointer">Add Student</button>
                <a href="students.php" class="bg-transparent cursor-pointer hover:underline">Back</a>
            </div>
        </form>
</body>

</html>