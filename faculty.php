<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get all faculty with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

$stmt = mysqli_prepare($conn, "SELECT * FROM faculty ORDER BY name ASC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$faculty_members = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get total count for pagination
$total_faculty = get_count($conn, 'faculty');
$total_pages = ceil($total_faculty / $per_page);

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="section-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Our Faculty</h1>
                <p class="lead mb-4">Meet our distinguished faculty members who are dedicated to academic excellence and student success.</p>
                <div class="course-stats d-flex justify-content-center gap-4">
                    <div class="stat-item">
                        <h3 class="mb-0"><?php echo number_format($total_faculty); ?>+</h3>
                        <p class="mb-0">Faculty Members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
  <?php if (empty($faculty_members)) : ?>
    <div class="text-center py-5">
      <div class="mb-4">
        <i class="fas fa-users-slash fa-4x text-muted"></i>
      </div>
      <h3 class="h4 text-muted">No faculty members found</h3>
    </div>
  <?php else : ?>
    <div class="row g-4">
      <?php foreach ($faculty_members as $faculty) : ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
          <div class="faculty-card card h-100 border-0 shadow-sm">
            <div class="position-relative">
              <?php if (!empty($faculty['image'])) : ?>
                <img src="assets/uploads/faculty/<?php echo htmlspecialchars($faculty['image']); ?>" 
                     alt="<?php echo htmlspecialchars($faculty['name']); ?>" 
                     class="card-img-top">
              <?php else : ?>
                <img src="assets/images/default-avatar.png" 
                     alt="Default profile" 
                     class="card-img-top">
              <?php endif; ?>
            </div>
            <div class="faculty-info">
              <h3 class="faculty-name"><?php echo htmlspecialchars($faculty['name']); ?></h3>
              <div class="faculty-position"><?php echo htmlspecialchars($faculty['position']); ?></div>
              <div class="faculty-department"><?php echo htmlspecialchars($faculty['department']); ?></div>
              <div class="faculty-bio">
                <?php echo substr(htmlspecialchars($faculty['bio']), 0, 100); ?>...
              </div>
              <div class="faculty-contact">
                <a href="mailto:<?php echo htmlspecialchars($faculty['email']); ?>" 
                   class="btn btn-sm btn-outline-primary">
                   <i class="fas fa-envelope me-1"></i> Contact
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($total_pages > 1) : ?>
      <nav aria-label="Faculty pagination" class="mt-5">
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
