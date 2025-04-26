<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = 'Invalid message ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$message_id = (int)$_GET['id'];

// First check if message exists
$stmt = mysqli_prepare($conn, "SELECT id FROM contact_messages WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $message_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = 'Message not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}
mysqli_stmt_close($stmt);

// Delete the message
$delete_stmt = mysqli_prepare($conn, "DELETE FROM contact_messages WHERE id = ?");
mysqli_stmt_bind_param($delete_stmt, "i", $message_id);

if (mysqli_stmt_execute($delete_stmt)) {
    $_SESSION['message'] = 'Message deleted successfully';
    $_SESSION['message_type'] = 'success';
} else {
    $_SESSION['message'] = 'Error deleting message: ' . mysqli_error($conn);
    $_SESSION['message_type'] = 'danger';
}

mysqli_stmt_close($delete_stmt);
header('Location: index.php');
exit;