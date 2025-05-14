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
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $fee = mysqli_real_escape_string($conn, $_POST['fee']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);

    // Handle image upload
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    // Generate unique filename
    $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
    $new_image_name = uniqid() . '.' . $image_ext;
    $upload_dir = "../../assets/uploads//courses/";
    
    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $image_folder = $upload_dir . $new_image_name;

    if ($image_error === 0) {
        if ($image_size > 5242880) {
            $_SESSION['message'] = "Image size is too large!";
            $_SESSION['message_type'] = 'danger';
            header('Location: add.php');
            exit;
        } else {
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO courses (name, description, duration, fee, requirements, image, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                mysqli_stmt_bind_param($stmt, "ssssss", $name, $description, $duration, $fee, $requirements, $image_name);
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = 'Course added successfully';
                    $_SESSION['message_type'] = 'success';
                } else {
                    $_SESSION['message'] = 'Error adding course: ' . mysqli_error($conn);
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
include '../../includes/admin-header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">Add Course</h2>
            <a href="../dashboard.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">← Back</a>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['message']; 
                        unset($_SESSION['message']); 
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="add.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Course Name:</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" name="description" id="description" rows="5" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="duration" class="form-label">Duration:</label>
                    <input type="text" class="form-control" name="duration" id="duration" required>
                </div>
                
                <div class="mb-3">
                    <label for="fee" class="form-label">Fee:</label>
                    <input type="text" class="form-control" name="fee" id="fee" required>
                </div>
                
                <div class="mb-3">
                    <label for="requirements" class="form-label">Requirements:</label>
                    <textarea class="form-control" name="requirements" id="requirements" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <input class="form-control" type="file" name="image" id="image" required>
                    <div class="form-text">Maximum file size: 5MB</div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>