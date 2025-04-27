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

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $message_id = (int)$_GET['id'];

    $stmt = mysqli_prepare($conn, "UPDATE contact_messages SET status = 'read' WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $message_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "invalid";
}