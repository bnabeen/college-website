<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get all images with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12; // Show 12 images per page
$offset = ($page - 1) * $per_page;

// Get images with optional category filter
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : null;

if ($category) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM gallery WHERE category = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, "sii", $category, $per_page, $offset);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM gallery ORDER BY created_at DESC LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$images = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get total count for pagination
$where_clause = $category ? "WHERE category = '" . mysqli_real_escape_string($conn, $category) . "'" : '';
$total_images = get_count($conn, 'gallery', $where_clause);
$total_pages = ceil($total_images / $per_page);

// Get all categories for filter
$cat_stmt = mysqli_query($conn, "SELECT DISTINCT category FROM gallery ORDER BY category");
$categories = mysqli_fetch_all($cat_stmt, MYSQLI_ASSOC);

include 'includes/header.php';
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">Our Gallery</h1>
        <p class="text-muted">Explore our collection of memorable moments and achievements</p>
    </div>

    <!-- Category Filter -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <a href="gallery.php" class="btn btn-sm <?php echo !$category ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-4">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?php echo urlencode($cat['category']); ?>" 
                       class="btn btn-sm <?php echo $category === $cat['category'] ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-4">
                        <?php echo htmlspecialchars($cat['category']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (empty($images)): ?>
        <div class="text-center py-5">
            <p class="text-muted">No images found in this category.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($images as $image): ?>
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm gallery-card">
                        <img src="assets/uploads/gallery/<?php echo htmlspecialchars($image['image']); ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($image['title']); ?>"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars($image['description']); ?></p>
                            <a href="assets/uploads/gallery/<?php echo htmlspecialchars($image['image']); ?>" 
                               class="btn btn-sm btn-primary rounded-pill px-4"
                               data-bs-toggle="modal" 
                               data-bs-target="#imageModal"
                               data-image="assets/uploads/gallery/<?php echo htmlspecialchars($image['image']); ?>"
                               data-title="<?php echo htmlspecialchars($image['title']); ?>"
                               data-description="<?php echo htmlspecialchars($image['description']); ?>">
                                View Larger
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="" class="img-fluid" alt="">
                        <p class="text-muted mt-3 px-4"></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Gallery pagination" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    imageModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const image = button.getAttribute('data-image');
        const title = button.getAttribute('data-title');
        const description = button.getAttribute('data-description');
        
        const modal = this;
        modal.querySelector('.modal-title').textContent = title;
        modal.querySelector('.modal-body img').src = image;
        modal.querySelector('.modal-body p').textContent = description;
    });
});
</script>

<?php include 'includes/footer.php'; ?>