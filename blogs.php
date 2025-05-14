<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Add pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9; // Show 9 blogs per page
$offset = ($page - 1) * $per_page;

// Fetch all published blogs with pagination
$query = "SELECT b.*, u.username as author 
          FROM blogs b 
          LEFT JOIN users u ON b.author_id = u.id 
          WHERE b.status = 'published' 
          ORDER BY b.created_at DESC 
          LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get total count for pagination
$total_blogs = get_count($conn, 'blogs', "status = 'published'");
$total_pages = ceil($total_blogs / $per_page);

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="section-hero mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="display-4 fw-bold mb-3">Latest Blogs</h1>
                <p class="lead mb-0">Stay updated with the latest happenings at Nepal College of Technology</p>
            </div>
        </div>
    </div>
</div>

<!-- Blogs Section -->
<section class="blogs-section py-5">
    <div class="container">
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($blog = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 blog-card shadow-sm">
                            <?php if (!empty($blog['image'])): ?>
                                <div class="blog-image-wrapper">
                                    <img src="assets/uploads/blogs/<?php echo htmlspecialchars($blog['image']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="blog-meta mb-2">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?php echo format_date($blog['created_at']); ?>
                                        <?php if (!empty($blog['author'])): ?>
                                            <span class="mx-2">|</span>
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($blog['author']); ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo substr(strip_tags($blog['content']), 0, 150); ?>...
                                </p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="blog-single.php?id=<?php echo $blog['id']; ?>" 
                                   class="btn btn-primary btn-sm">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="col-12">
                        <nav aria-label="Blog pagination" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo ($page - 1); ?>">Previous</a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No blogs available at the moment.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php include 'includes/footer.php'; ?>