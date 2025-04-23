<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// check the session status
if (session_status() = PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['user_id'])) {
    header ('Location: ../../auth/login.php');
    exit();
}

if (['REQUEST_METHOD'] === "POST") {

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $id = $_SESSION['user_id'];
    $status = $_POST['status'];

    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    $image_folder = "C:/xampp/htdocs/learn-php/college-website/assets/uploads/gallary/" . basename($image_name);

    if ($image_error === 0) {
        if ($image_size > 5242880) {
            $_SESSION['message'] = "Image size is too large!";
            $_SESSION['message_type'] = 'danger';
            header('Location: add.php');
            exit;
        } else {
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                
                $stmt = mysqli_prepare($conn, "INSERT INTO gallary (id,	title, description, image, category, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                mysqli_stmt_bind_param($stmt, "sssis", $id, $title, $description, $image_name,  $category, $status);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = 'Blog added successfully';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error adding blog: ' . mysqli_error($conn);
                    $_SESSION['message_type'] = 'danger';
                }
                mysqli_stmt_close($stmt);
            }
        }
    } else {
        $_SESSION['message'] = "Error uploading image!";
        $_SESSION['message_type'] = 'danger';
        header('Location: add.php');
        exit;
    }
}