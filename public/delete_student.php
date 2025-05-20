<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $db->prepare("DELETE FROM students WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => "Student ID #$id deleted successfully",
        ];
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => "Failed to delete Student ID #$id",
        ];
    }
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => "Failed to delete Student ID #$id, Invalid Student ID",
    ];
}

// Redirect back to student list
header("Location: students.php");
exit;
