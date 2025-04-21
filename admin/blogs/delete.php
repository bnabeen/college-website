<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Start session and verify user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = 'Invalid blog ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

// Check if blog exists
$stmt = mysqli_prepare($conn, "SELECT id FROM blogs WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Store result for row counting
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    $_SESSION['message'] = 'Blog not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

mysqli_stmt_close($stmt);

// Delete blog
$stmt = mysqli_prepare($conn, "DELETE FROM blogs WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['message'] = 'Blog deleted successfully';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Error deleting blog: ' . mysqli_connect_error();
    $_SESSION['message_type'] = 'danger';
}
mysqli_stmt_close($stmt);

// Redirect back to the blog listing
header('Location: index.php');
exit;
?>