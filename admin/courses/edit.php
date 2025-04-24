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

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = 'Invalid course ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$course_id = (int)$_GET['id'];

// Fetch course details
$stmt = mysqli_prepare($conn, "SELECT * FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $course_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = 'Course not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$course = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $fee = mysqli_real_escape_string($conn, $_POST['fee']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);

    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];
        
        if ($image_error === 0) {
            if ($image_size > 5242880) {
                $_SESSION['message'] = "Image size is too large!";
                $_SESSION['message_type'] = 'danger';
            } else {
                $image_folder = "../../assets/images/" . basename($image_name);
                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    // Delete old image if exists
                    if (!empty($course['image'])) {
                        $old_image = "../../assets/images/" . $course['image'];
                        if (file_exists($old_image)) {
                            unlink($old_image);
                        }
                    }
                } else {
                    $_SESSION['message'] = "Failed to upload image";
                    $_SESSION['message_type'] = 'danger';
                }
            }
        }
    } else {
        $image_name = $course['image']; // Keep existing image
    }

    // Update course
    $stmt = mysqli_prepare($conn, "UPDATE courses SET name = ?, description = ?, duration = ?, fee = ?, requirements = ?, image = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $description, $duration, $fee, $requirements, $image_name, $course_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = 'Course updated successfully';
        $_SESSION['message_type'] = 'success';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['message'] = 'Error updating course: ' . mysqli_error($conn);
        $_SESSION['message_type'] = 'danger';
    }
    mysqli_stmt_close($stmt);
}

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Edit Course</h2>
        <a href="index.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">⬅ Back</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Course Name:</label>
                    <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($course['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" name="description" id="description" rows="5" required><?php echo htmlspecialchars($course['description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration:</label>
                    <input type="text" class="form-control" name="duration" id="duration" value="<?php echo htmlspecialchars($course['duration']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fee" class="form-label">Fee:</label>
                    <input type="text" class="form-control" name="fee" id="fee" value="<?php echo htmlspecialchars($course['fee']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="requirements" class="form-label">Requirements:</label>
                    <textarea class="form-control" name="requirements" id="requirements" rows="3" required><?php echo htmlspecialchars($course['requirements']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <?php if (!empty($course['image'])): ?>
                        <div class="mb-2">
                            <img src="../../assets/images/<?php echo htmlspecialchars($course['image']); ?>" 
                                alt="Current course image" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                    <input class="form-control" type="file" name="image" id="image">
                    <div class="form-text">Maximum file size: 5MB. Leave empty to keep current image.</div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>