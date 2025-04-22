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
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = $_SESSION['user_id'];
    $status = $_POST['status'];

    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_error = $_FILES['image']['error'];

    
    $image_folder = "C:/xampp/htdocs/learn-php/college-website/assets/uploads/blogs/" . basename($image_name);

    if ($image_error === 0) {
        if ($image_size > 5242880) {
            $_SESSION['message'] = "Image size is too large!";
            $_SESSION['message_type'] = 'danger';
            header('Location: add.php');
            exit;
        } else {
            if (move_uploaded_file($image_tmp_name, $image_folder)) {
                $stmt = mysqli_prepare($conn, "INSERT INTO blogs (title, content, image, author_id, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                mysqli_stmt_bind_param($stmt, "sssis", $title, $content, $image_name, $author_id, $status);
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
include '../../includes/admin-header.php';
?>
    <div class="container py-5">
        <div class="row justify-content-center">
        <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-primary">Add Blog</h2>
                    <a href="../dashboard.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">⬅ Back</a>
                    </div>
                    <div class="card-body">
                        <!-- Display session messages -->
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                <?php 
                                    echo $_SESSION['message']; 
                                    unset($_SESSION['message']); // Clear message after display
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="add.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title:</label>
                                <input type="text" class="form-control" name="title" id="title" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Content:</label>
                                <textarea class="form-control" name="content" id="content" rows="8" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Image:</label>
                                <input class="form-control" type="file" name="image" id="image" required>
                                <div class="form-text">Maximum file size: 5MB</div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Add Blog</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>