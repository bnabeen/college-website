<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = get_user_by_id($conn, $user_id);

if (!$user) {
    set_flash_message("User not found!", "danger");
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($conn, $_POST['name']);
    $email = sanitize_input($conn, $_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    $new_image_name = $user['profile_image']; // Initialize with current image

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $upload_dir = "../assets/images/admin/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $image_name = $_FILES['profile_image']['name'];
        $image_tmp = $_FILES['profile_image']['tmp_name'];
        $image_size = $_FILES['profile_image']['size'];
        $image_error = $_FILES['profile_image']['error'];
        
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($image_ext, $allowed)) {
            if ($image_error === 0) {
                if ($image_size <= 5242880) { // 5MB
                    $new_image_name = uniqid('profile_') . '.' . $image_ext;
                    $upload_path = $upload_dir . $new_image_name;
                    
                    if (move_uploaded_file($image_tmp, $upload_path)) {
                        // Delete old image if exists and different from default
                        if (!empty($user['profile_image']) && $user['profile_image'] !== 'default-avatar.png') {
                            $old_image = $upload_dir . $user['profile_image'];
                            if (file_exists($old_image)) {
                                unlink($old_image);
                            }
                        }
                    } else {
                        $errors[] = "Failed to upload image. Please check directory permissions.";
                        error_log("Failed to upload image to: " . $upload_path);
                    }
                } else {
                    $errors[] = "Image file is too large (max 5MB)";
                }
            } else {
                $errors[] = "Error uploading image: " . $image_error;
            }
        } else {
            $errors[] = "Invalid image format. Allowed formats: " . implode(', ', $allowed);
        }
    }

    // Update user profile if no errors
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, email = ?, profile_image = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $new_image_name, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update session data
            $_SESSION['user_name'] = $name;
            $_SESSION['profile_image'] = $new_image_name;
            
            set_flash_message("Profile updated successfully!", "success");
            header('Location: profile.php');
            exit;
        } else {
            $errors[] = "Error updating profile: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}

include '../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary mb-0">My Profile</h2>
                <a href="dashboard.php" class="btn btn-outline-primary rounded-pill px-4">← Back</a>
            </div>

            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

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
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <img src="<?php 
                                    echo !empty($user['profile_image']) 
                                        ? '../assets/images/admin/' . htmlspecialchars($user['profile_image']) 
                                        : '../assets/images/admin/default-avatar.png'; 
                                    ?>" 
                                    alt="Profile Image" 
                                    class="rounded-circle profile-image" 
                                    style="width: 120px; height: 120px; object-fit: cover;">
                                <label for="profile_image" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm" style="cursor: pointer;">
                                    <i class="fas fa-camera text-primary"></i>
                                </label>
                                <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/jpeg,image/png,image/gif">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                value="<?php echo htmlspecialchars($users['username'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?php echo htmlspecialchars($users['email'] ?? ''); ?>" required>
                        </div>


                        <hr class="my-4">

                        <h5 class="card-title mb-4">Change Password</h5>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            <div class="form-text">Leave blank to keep current password</div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                   minlength="8">
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                   minlength="8">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_image').onchange = function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};
</script>

<?php include '../includes/admin-footer.php'; ?>