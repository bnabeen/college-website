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
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) === 0) {
    $_SESSION['message'] = 'Blog not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$blog = $result->fetch_assoc();
$stmt->close();

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
        $stmt = $conn->prepare("UPDATE blogs SET title = ?, content = ?, image = ?, author_id = ?, status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("sssisi", $title, $content, $image, $author_id, $status, $id);

        
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Blog updated successfully';
            $_SESSION['message_type'] = 'success';
            header('Location: index.php');
            exit;
        } else {
            $errors[] = "Error updating blog: " . $conn->error;
        }
        $stmt->close();
    }
}

include '../../includes/admin-header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Blog</h1>
        <a href="index.php" class="btn btn-secondary">Back to List</a>
    </div>
    
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="excerpt">Excerpt</label>
                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?php echo htmlspecialchars($blog['excerpt']); ?></textarea>
                    <small class="form-text text-muted">A short summary of the blog post.</small>
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="draft" <?php echo $blog['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                        <option value="published" <?php echo $blog['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Blog</button>
            </form>
        </div>
    </div>
</div>



<?php include '../../includes/admin-footer.php'; ?>