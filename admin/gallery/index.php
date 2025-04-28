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

// Get all images with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12; // Show 12 images per page
$offset = ($page - 1) * $per_page;

$stmt = mysqli_prepare($conn, "SELECT * FROM gallery ORDER BY created_at DESC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$images = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get total count for pagination
$total_images = get_count($conn, 'gallery');
$total_pages = ceil($total_images / $per_page);

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Gallery Management</h2>
        <div>
            <a href="upload.php" class="btn btn-primary rounded-pill px-4">+ Add New Image</a>
            <a href="../dashboard.php" class="btn btn-outline-primary rounded-pill px-4 ms-2">← Back</a>
        </div>
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
        <div class="card-body">
            <?php if (empty($images)): ?>
                <p class="text-muted text-center py-4">No images found in gallery.</p>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($images as $image): ?>
                        <div class="col-md-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="../../assets/uploads/gallery/<?php echo htmlspecialchars($image['image'] ?? ''); ?>" 
                                     class="card-img-top" 
                                     alt="<?php echo htmlspecialchars($image['title'] ?? ''); ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate"><?php echo htmlspecialchars($image['title']); ?></h5>
                                    <p class="card-text small text-muted mb-2">
                                        Added: <?php echo format_date($image['created_at']); ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="../../assets/uploads/gallery/<?php echo htmlspecialchars($image['image'] ?? ''); ?>" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3"
                                           target="_blank"><i class="fas fa-eye"></i> View</a>
                                        <a href="delete.php?id=<?php echo $image['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                           onclick="return confirm('Are you sure you want to delete this image?')"><i class="fas fa-trash-alt"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Gallery pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?>