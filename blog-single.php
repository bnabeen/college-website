<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get blog ID from URL
$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch blog post
$query = "SELECT b.*, u.username as author_name 
          FROM blogs b 
          LEFT JOIN users u ON b.author_id = u.id 
          WHERE b.id = ? AND b.status = 'published'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $blog_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$blog = mysqli_fetch_assoc($result);

// If blog not found, redirect to blogs page
if (!$blog) {
    header('Location: blogs.php');
    exit();
}

// Fetch related posts
$related_query = "SELECT * FROM blogs 
                 WHERE status = 'published' 
                 AND id != ? 
                 ORDER BY created_at DESC 
                 LIMIT 3";
$stmt = mysqli_prepare($conn, $related_query);
mysqli_stmt_bind_param($stmt, "i", $blog_id);
mysqli_stmt_execute($stmt);
$related_result = mysqli_stmt_get_result($stmt);
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .blog-content {
            line-height: 1.8;
            font-size: 1.1rem;
        }
        .blog-content img {
            max-width: 100%;
            height: auto;
            margin: 2rem 0;
        }
        .related-post-img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <main class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <article class="blog-post">
                    <h1 class="display-4 mb-3"><?php echo htmlspecialchars($blog['title']); ?></h1>
                    <div class="d-flex align-items-center mb-4">
                        <img src="assets/images/default-avatar.png" class="rounded-circle me-2" width="40" height="40" alt="Author">
                        <div>
                            <div class="fw-bold"><?php echo htmlspecialchars($blog['author_name'] ?? 'Admin'); ?></div>
                            <div class="text-muted small">
                                Published on <?php echo format_date($blog['created_at']); ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($blog['image'])): ?>
                        <img src="assets/uploads/blogs/<?php echo $blog['image']; ?>" 
                             class="img-fluid mb-4 rounded" 
                             alt="<?php echo htmlspecialchars($blog['title']); ?>">
                    <?php endif; ?>

                    <div class="blog-content">
                        <?php echo $blog['content']; ?>
                    </div>

                    <hr class="my-5">

                    <!-- Social Share Buttons -->
                    <div class="d-flex gap-2 mb-5">
                        <a href="#" class="btn btn-primary">Share on Facebook</a>
                        <a href="#" class="btn btn-info text-white">Share on Twitter</a>
                        <a href="#" class="btn btn-success">Share on WhatsApp</a>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    <!-- Related Posts -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Related Posts</h5>
                        </div>
                        <div class="card-body">
                            <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                                <div class="mb-3">
                                    <?php if (!empty($related['image'])): ?>
                                        <img src="assets/uploads/blogs/<?php echo $related['image']; ?>" 
                                             class="img-fluid mb-2 related-post-img w-100" 
                                             alt="<?php echo htmlspecialchars($related['title']); ?>">
                                    <?php endif; ?>
                                    <h6>
                                        <a href="blog-single.php?id=<?php echo $related['id']; ?>" 
                                           class="text-decoration-none">
                                            <?php echo htmlspecialchars($related['title']); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <?php echo format_date($related['created_at']); ?>
                                    </small>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php include 'includes/footer.php'; ?>