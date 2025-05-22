<?php
session_start();

require_once '../src/functions.php';
require_once base_path('src/db.php');

$db = createPdo();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $db->prepare("SELECT course_name FROM courses WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    $courseName = $course ? $course['course_name'] : null;

    try {
        $updateStmt = $db->prepare("UPDATE students SET course_id = NULL WHERE course_id = :id");
        $updateStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $updateStmt->execute();

        $deleteStmt = $db->prepare("DELETE FROM courses WHERE id = :id");
        $deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($deleteStmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => $courseName
                    ? "Course \"$courseName\" deleted successfully!"
                    : "Course deleted successfully!",
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => $courseName
                    ? "Failed to delete course \"$courseName\""
                    : "Failed to delete course",
            ];
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => $courseName
                ? "Failed to delete course \"$courseName\""
                : "Failed to delete course",
        ];
    }
} else {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => "Failed to delete course, Invalid Course ID",
    ];
}

header("Location: courses.php");
exit;
