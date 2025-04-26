<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get all images with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Get images with optional category filter
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : null;

// Get images with search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : null;

// Build query based on filters
if ($category && $search) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM gallery WHERE category = ? AND (title LIKE ? OR description LIKE ?) ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $search_term = "%$search%";
    mysqli_stmt_bind_param($stmt, "sssii", $category, $search_term, $search_term, $per_page, $offset);
} elseif ($category) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM gallery WHERE category = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, "sii", $category, $per_page, $offset);
} elseif ($search) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM gallery WHERE title LIKE ? OR description LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $search_term = "%$search%";
    mysqli_stmt_bind_param($stmt, "ssii", $search_term, $search_term, $per_page, $offset);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM gallery ORDER BY created_at DESC LIMIT ? OFFSET ?");
    mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$images = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get total count for pagination
$where_clause = '';
if ($category && $search) {
    $where_clause = "WHERE category = '" . mysqli_real_escape_string($conn, $category) . "' AND (title LIKE '%$search%' OR description LIKE '%$search%')";
} elseif ($category) {
    $where_clause = "WHERE category = '" . mysqli_real_escape_string($conn, $category) . "'";
} elseif ($search) {
    $where_clause = "WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}
$total_images = get_count($conn, 'gallery', $where_clause);
$total_pages = ceil($total_images / $per_page);

// Get all categories for filter
$cat_stmt = mysqli_query($conn, "SELECT DISTINCT category FROM gallery ORDER BY category");
$categories = mysqli_fetch_all($cat_stmt, MYSQLI_ASSOC);

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="gallery-hero position-relative overflow-hidden py-5 mb-5">
    <div class="container position-relative z-1">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 text-white fw-bold mb-4">Our Gallery</h1>
                <p class="lead text-white-50 mb-4">Explore our collection of memorable moments and achievements at NCT</p>
                
                <!-- Search Form -->
                <form action="" method="GET" class="gallery-search">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg" 
                               placeholder="Search gallery..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                        <?php if ($category): ?>
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                        <?php endif; ?>
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="gallery-hero-overlay"></div>
</div>

<div class="container pb-5">
    <!-- Category Filter -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-10">
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <a href="gallery.php" class="btn <?php echo !$category ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-4">
                    <i class="fas fa-images me-2"></i>All
                </a>
                <?php foreach ($categories as $cat): ?>
                    <a href="?category=<?php echo urlencode($cat['category']); ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="btn <?php echo $category === $cat['category'] ? 'btn-primary' : 'btn-outline-primary'; ?> rounded-pill px-4">
                        <?php echo htmlspecialchars($cat['category']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (empty($images)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-image-slash fa-4x text-muted"></i>
            </div>
            <h3 class="h4 text-muted">No images found</h3>
            <p class="text-muted mb-4">Try adjusting your search or filter criteria</p>
            <a href="gallery.php" class="btn btn-primary rounded-pill px-4">View All Images</a>
        </div>
    <?php else: ?>
        <!-- Gallery Grid -->
        <div class="row g-4">
            <?php foreach ($images as $image): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="gallery-item card h-100 border-0 shadow-sm">
                        <div class="card-img-wrap">
                            <img src="assets/images/gallery/<?php echo htmlspecialchars($image['image']); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($image['title']); ?>">
                            <div class="gallery-overlay">
                                <button class="btn btn-light rounded-circle" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#imageModal"
                                        data-image="assets/images/gallery/<?php echo htmlspecialchars($image['image']); ?>"
                                        data-title="<?php echo htmlspecialchars($image['title']); ?>"
                                        data-description="<?php echo htmlspecialchars($image['description']); ?>">
                                    <i class="fas fa-expand-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                            <p class="card-text small text-muted"><?php echo htmlspecialchars(substr($image['description'], 0, 100)); ?>...</p>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <?php echo date('M d, Y', strtotime($image['created_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Gallery pagination" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" aria-label="Previous">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" aria-label="Next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title h4"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" class="img-fluid w-100" alt="">
                <div class="p-4">
                    <p class="text-muted mb-0"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>