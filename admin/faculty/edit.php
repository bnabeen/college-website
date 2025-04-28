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

// Get faculty details
$stmt = mysqli_prepare($conn, "SELECT * FROM faculty WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $faculty_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = 'Faculty member not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$faculty = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    
    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];

        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $new_image_name = uniqid() . '.' . $image_ext;
        $image_folder = "../../assets/uploads//faculty/" . $new_image_name;

        if ($image_error === 0) {
            if ($image_size > 5242880) {
                $_SESSION['message'] = "Image size is too large!";
                $_SESSION['message_type'] = 'danger';
            } else {
                if (move_uploaded_file($image_tmp_name, $image_folder)) {
                    // Delete old image if exists
                    if (!empty($faculty['image'])) {
                        $old_image = "../../assets/uploads//faculty/" . $faculty['image'];
                        if (file_exists($old_image)) {
                            unlink($old_image);
                        }
                    }
                } else {
                    $_SESSION['message'] = "Failed to upload image!";
                    $_SESSION['message_type'] = 'danger';
                }
            }
        }
    } else {
        $new_image_name = $faculty['image']; // Keep existing image
    }

    // Update faculty
    $stmt = mysqli_prepare($conn, "UPDATE faculty SET name = ?, position = ?, department = ?, email = ?, bio = ?, image = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $position, $department, $email, $bio, $new_image_name, $faculty_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = 'Faculty member updated successfully';
        $_SESSION['message_type'] = 'success';
        header('Location: index.php');
        exit;
    } else {
        $_SESSION['message'] = 'Error updating faculty member: ' . mysqli_error($conn);
        $_SESSION['message_type'] = 'danger';
    }
    mysqli_stmt_close($stmt);
}

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">Edit Faculty Member</h2>
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
                            <input type="text" class="form-control" name="name" id="name" 
                                   value="<?php echo htmlspecialchars($faculty['name']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label">Position:</label>
                            <input type="text" class="form-control" name="position" id="position" 
                                   value="<?php echo htmlspecialchars($faculty['position']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="department" class="form-label">Department:</label>
                            <input type="text" class="form-control" name="department" id="department" 
                                   value="<?php echo htmlspecialchars($faculty['department']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" 
                                   value="<?php echo htmlspecialchars($faculty['email']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio:</label>
                            <textarea class="form-control" name="bio" id="bio" rows="4" required><?php echo htmlspecialchars($faculty['bio']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Profile Image:</label>
                            <?php if (!empty($faculty['image'])): ?>
                                <div class="mb-2">
                                    <img src="../../assets/uploads//faculty/<?php echo htmlspecialchars($faculty['image']); ?>" 
                                         alt="Current profile image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                            <input class="form-control" type="file" name="image" id="image" accept="image/*">
                            <div class="form-text">
                                Maximum file size: 5MB<br>
                                Allowed formats: JPG, JPEG, PNG<br>
                                Leave empty to keep current image
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Faculty Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>