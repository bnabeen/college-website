<?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

// Start session and verify user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = 'Invalid blog ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$blog_id = (int)$_GET['id'];

// Fetch blog details
$stmt = mysqli_prepare($conn, "SELECT * FROM blogs WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $blog_id);
mysqli_stmt_execute($stmt);

// Get result set from statement
$result = mysqli_stmt_get_result($stmt); 

if (mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = 'Blog not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$blog = mysqli_fetch_assoc($result); 
mysqli_stmt_close($stmt);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $excerpt = trim($_POST['excerpt']);
    $status = $_POST['status'];
    $errors = [];

    // Validate input
    if (empty($title)) {
        $errors[] = "Title is required";
    }
    
    if (empty($content)) {
        $errors[] = "Content is required";
    }

    if (empty($errors)) {
        // Update blog post
        $stmt = mysqli_prepare($conn, "UPDATE blogs SET title = ?, content = ?, image = ?, author_id = ?, status = ?, updated_at = NOW() WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssisi", $title, $content, $image, $author_id, $status, $id);

        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = 'Blog updated successfully';
            $_SESSION['message_type'] = 'success';
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Error updating blog: " . mysqli_connect_error();
        }
        mysqli_stmt_close($stmt);
    }
}

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Edit Blog</h2>
        <a href="../blogs/edit.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">← Back</a>
    </div>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="title" class="form-label fw-semibold">Title</label>
                    <input type="text" class="form-control rounded-3" id="title" name="title" 
                        value="<?php echo htmlspecialchars($blog['title']); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="excerpt" class="form-label fw-semibold">Excerpt</label>
                    <textarea class="form-control rounded-3" id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($blog['excerpt']); ?></textarea>
                    <small class="form-text text-muted">A short summary of the blog post.</small>
                </div>

                <div class="mb-4">
                    <label for="content" class="form-label fw-semibold">Content</label>
                    <textarea class="form-control rounded-3" id="content" name="content" rows="10" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select class="form-select rounded-3" id="status" name="status">
                        <option value="draft" <?php echo $blog['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $blog['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-4">Update Blog</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php include '../../includes/admin-footer.php'; ?>