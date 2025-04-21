<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch all published blogs
$query = "SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

include 'includes/header.php';
?>

<section class="blogs-section py-5">
    <div class="container">
        <h2 class="mb-4">Latest Blogs & Events</h2>
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($blog = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($blog['image'])): ?>
                                <img src="assets/images/<?php echo $blog['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h5>
                                <p class="card-text"><?php echo substr(strip_tags($blog['content']), 0, 120); ?>...</p>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted"><?php echo format_date($blog['created_at']); ?></small>
                                <a href="blog-single.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-primary float-end">Read More</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No blogs available at the moment.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
