<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = 'Invalid faculty ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$faculty_id = (int)$_GET['id'];

// First get the faculty details to delete the image
$stmt = mysqli_prepare($conn, "SELECT image FROM faculty WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $faculty_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($faculty = mysqli_fetch_assoc($result)) {
    // Delete the image file if it exists
    if (!empty($faculty['image'])) {
        $image_path = "../../assets/uploads//faculty/" . $faculty['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Delete the faculty record
    $delete_stmt = mysqli_prepare($conn, "DELETE FROM faculty WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, "i", $faculty_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        $_SESSION['message'] = 'Faculty member deleted successfully';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error deleting faculty member: ' . mysqli_error($conn);
        $_SESSION['message_type'] = 'danger';
    }
    mysqli_stmt_close($delete_stmt);
} else {
    $_SESSION['message'] = 'Faculty member not found';
    $_SESSION['message_type'] = 'danger';
}

mysqli_stmt_close($stmt);
header('Location: index.php');
exit;