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
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Handle image upload
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    // Generate unique filename
    $image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
    $new_image_name = uniqid() . '.' . $image_ext;
    $image_folder = "../../assets/uploads/gallery/" . $new_image_name;
    
    if ($image_error === 0) {
        if ($image_size > 5242880) { // 5MB limit
            $_SESSION['message'] = "Image size is too large!";
            $_SESSION['message_type'] = 'danger';
        } else {
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO gallery (title, description, image, category, created_at) VALUES (?, ?, ?, ?, NOW())");
                mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $new_image_name, $category);
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['message'] = 'Image uploaded successfully';
                    $_SESSION['message_type'] = 'success';
                    header('Location: index.php');
                    exit;
                }
                // Delete uploaded file if database insert fails
                unlink($image_folder);
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
                <h2 class="fw-bold text-primary">Upload Image</h2>
                <a href="index.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">⬅ Back</a>
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
                            <label for="title" class="form-label">Image Title:</label>
                            <input type="text" class="form-control" name="title" id="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category:</label>
                            <input type="text" class="form-control" name="category" id="category" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Select Image:</label>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*" required>
                            <div class="form-text">
                                Maximum file size: 5MB<br>
                                Allowed formats: JPG, JPEG, PNG, GIF
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Upload Image</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>