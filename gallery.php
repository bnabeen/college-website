<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get all images with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
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

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="section-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Our Gallery</h1>
                <p class="lead mb-4">Explore our collection of memorable moments and achievements at NCT.</p>
                <div class="course-stats d-flex justify-content-center gap-4">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
  <?php if (empty($images)) : ?>
    <div class="text-center py-5">
      <div class="mb-4">
        <i class="fas fa-image-slash fa-4x text-muted"></i>
      </div>
      <h3 class="h4 text-muted">No images found</h3>
    </div>
  <?php else : ?>
    <div class="row g-4">
      <?php foreach ($images as $image) : ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="gallery-item card border-0 shadow-sm">
            <div class="card-img-wrap position-relative overflow-hidden rounded">
              <img src="assets/uploads/gallery/<?php echo htmlspecialchars($image['image']); ?>" alt="<?php echo htmlspecialchars($image['title']); ?>" class="card-img-top">
              <div class="image-title-overlay">
                <p><?php echo htmlspecialchars($image['title']); ?></p>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1) : ?>
      <nav aria-label="Gallery pagination" class="mt-5">
        <ul class="pagination justify-content-center">
          <?php if ($page > 1) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                <i class="fas fa-chevron-left"></i>
              </a>
            </li>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>

          <?php if ($page < $total_pages) : ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                <i class="fas fa-chevron-right"></i>
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
