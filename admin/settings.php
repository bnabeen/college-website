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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => sanitize_input($conn, $_POST['site_name']),
        'site_description' => sanitize_input($conn, $_POST['site_description']),
        'contact_email' => sanitize_input($conn, $_POST['contact_email']),
        'contact_phone' => sanitize_input($conn, $_POST['contact_phone']),
        'address' => sanitize_input($conn, $_POST['address']),
        'facebook_url' => sanitize_input($conn, $_POST['facebook_url']),
        'twitter_url' => sanitize_input($conn, $_POST['twitter_url']),
        'linkedin_url' => sanitize_input($conn, $_POST['linkedin_url']),
        'instagram_url' => sanitize_input($conn, $_POST['instagram_url'])
    ];

    foreach ($settings as $key => $value) {
        $stmt = mysqli_prepare($conn, "INSERT INTO settings (setting_name, setting_value) 
                                     VALUES (?, ?) 
                                     ON DUPLICATE KEY UPDATE setting_value = ?");
        mysqli_stmt_bind_param($stmt, "sss", $key, $value, $value);
        
        if (!mysqli_stmt_execute($stmt)) {
            set_flash_message("Error updating settings: " . mysqli_error($conn), "danger");
            break;
        }
        mysqli_stmt_close($stmt);
    }

    set_flash_message("Settings updated successfully!", "success");
    header('Location: settings.php');
    exit;
}

// Get current settings
$settings = [];
$result = mysqli_query($conn, "SELECT setting_name, setting_value FROM settings");
while ($row = mysqli_fetch_assoc($result)) {
    $settings[$row['setting_name']] = $row['setting_value'];
}
mysqli_free_result($result);

include '../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary mb-0">Site Settings</h2>
                <a href="dashboard.php" class="btn btn-outline-primary rounded-pill px-4">← Back</a>
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
                    <form method="POST" action="">
                        <!-- General Settings -->
                        <h5 class="card-title mb-4">General Settings</h5>
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name</label>
                            <input type="text" class="form-control" id="site_name" name="site_name" 
                                   value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="site_description" class="form-label">Site Description</label>
                            <textarea class="form-control" id="site_description" name="site_description" 
                                      rows="3" required><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                        </div>

                        <!-- Contact Information -->
                        <h5 class="card-title mb-4">Contact Information</h5>
                        <div class="mb-3">
                            <label for="contact_email" class="form-label">Contact Email</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                   value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="contact_phone" class="form-label">Contact Phone</label>
                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                   value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" 
                                      rows="2" required><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                        </div>

                        <!-- Social Media Links -->
                        <h5 class="card-title mb-4">Social Media Links</h5>
                        <div class="mb-3">
                            <label for="facebook_url" class="form-label">Facebook URL</label>
                            <input type="url" class="form-control" id="facebook_url" name="facebook_url" 
                                   value="<?php echo htmlspecialchars($settings['facebook_url'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="twitter_url" class="form-label">Twitter URL</label>
                            <input type="url" class="form-control" id="twitter_url" name="twitter_url" 
                                   value="<?php echo htmlspecialchars($settings['twitter_url'] ?? ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                            <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" 
                                   value="<?php echo htmlspecialchars($settings['linkedin_url'] ?? ''); ?>">
                        </div>

                        <div class="mb-4">
                            <label for="instagram_url" class="form-label">Instagram URL</label>
                            <input type="url" class="form-control" id="instagram_url" name="instagram_url" 
                                   value="<?php echo htmlspecialchars($settings['instagram_url'] ?? ''); ?>">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin-footer.php'; ?>