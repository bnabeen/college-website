<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get blog ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = "SELECT * FROM blogs WHERE id = $id AND status = 'published' LIMIT 1";
$result = mysqli_query($conn, $query);
$blog = mysqli_fetch_assoc($result);

include 'includes/header.php';
?>

<section class="blog-detail py-5">
    <div class="container">
        <?php if ($blog): ?>
            <h2 class="mb-3"><?php echo htmlspecialchars($blog['title']); ?></h2>
            <p class="text-muted"><?php echo format_date($blog['created_at']); ?></p>
            <?php if (!empty($blog['image'])): ?>
                <img src="assets/images/<?php echo $blog['image']; ?>" class="img-fluid rounded mb-4" alt="<?php echo htmlspecialchars($blog['title']); ?>">
            <?php endif; ?>
            <div>
                <?php echo nl2br($blog['content']); ?>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Blog post not found or has been removed.</div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
