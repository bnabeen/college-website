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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    // Generate unique filename
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    $new_image_name = uniqid() . '.' . $image_ext;
    $upload_dir = "../../assets/images/faculty/";
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $image_folder = $upload_dir . $new_image_name;

    if ($image_error === 0) {
        if ($image_size > 5242880) { // 5MB limit
            $_SESSION['message'] = "Image size is too large!";
            $_SESSION['message_type'] = 'danger';
        } else {
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO faculty (name, position, department, email, bio, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                mysqli_stmt_bind_param($stmt, "ssssss", $name, $position, $department, $email, $bio, $new_image_name);
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = 'Faculty member added successfully';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php');
                    exit;
                } else {
                    $_SESSION['message'] = 'Error adding faculty member: ' . mysqli_error($conn);
                    $_SESSION['message_type'] = 'danger';
                    unlink($image_folder); // Delete uploaded file if database insert fails
                }
                mysqli_stmt_close($stmt);
            } else {
                $_SESSION['message'] = "Failed to upload image!";
                $_SESSION['message_type'] = 'danger';
            }
        }
    } else {
        $_SESSION['message'] = "Error uploading image!";
        $_SESSION['message_type'] = 'danger';
    }
}

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">Add Faculty Member</h2>
                <a href="index.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">← Back</a>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name:</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position:</label>
                            <input type="text" class="form-control" name="position" id="position" required>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department:</label>
                            <input type="text" class="form-control" name="department" id="department" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio:</label>
                            <textarea class="form-control" name="bio" id="bio" rows="4" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Profile Image:</label>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*" required>
                            <div class="form-text">
                                Maximum file size: 5MB<br>
                                Allowed formats: JPG, JPEG, PNG
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Add Faculty Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>