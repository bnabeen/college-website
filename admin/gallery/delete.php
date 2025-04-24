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
    $_SESSION['message'] = 'Invalid image ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$image_id = (int)$_GET['id'];

// First get the image details to delete the file
$stmt = mysqli_prepare($conn, "SELECT image_name FROM gallery WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $image_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($image = mysqli_fetch_assoc($result)) {
    // Delete the image file if it exists
    if (!empty($image['image_name'])) {
        $image_path = "../../assets/images/gallery/" . $image['image_name'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Delete the image record from database
    $delete_stmt = mysqli_prepare($conn, "DELETE FROM gallery WHERE id = ?");
    mysqli_stmt_bind_param($delete_stmt, "i", $image_id);
    
    if (mysqli_stmt_execute($delete_stmt)) {
        $_SESSION['message'] = 'Image deleted successfully';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error deleting image: ' . mysqli_error($conn);
        $_SESSION['message_type'] = 'danger';
    }
    mysqli_stmt_close($delete_stmt);
} else {
    $_SESSION['message'] = 'Image not found';
    $_SESSION['message_type'] = 'danger';
}

mysqli_stmt_close($stmt);
header('Location: index.php');
exit;