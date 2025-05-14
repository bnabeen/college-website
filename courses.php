<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch all courses with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

$all_courses_sql = "SELECT * FROM courses ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = mysqli_prepare($conn, $all_courses_sql);
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$all_courses_result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// Get total count for pagination
$total_courses = get_count($conn, 'courses');
$total_pages = ceil($total_courses / $per_page);

$page_title = "Our Courses - " . SITE_NAME;
include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="section-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">Our Courses</h1>
                <p class="lead mb-4">Discover a wide range of courses designed to help you achieve your academic and professional goals.</p>
                <div class="course-stats d-flex justify-content-center gap-4">
                    <div class="stat-item">
                        <h3 class="mb-0"><?php echo number_format($total_courses); ?>+</h3>
                        <p class="mb-0">Total Courses</p>
                    </div>
                    <div class="stat-item">
                        <h3 class="mb-0">4+</h3>
                        <p class="mb-0">Years Experience</p>
                    </div>
                    <div class="stat-item">
                        <h3 class="mb-0">1000+</h3>
                        <p class="mb-0">Students</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Courses Section -->
<section class="all-courses py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">All Courses</h2>
        
        <!-- Course Filters -->
        <div class="course-filters mb-4">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-primary"></i>
                        </span>
                        <input type="text" class="form-control" id="courseSearch" 
                               placeholder="Search courses...">
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Grid -->
        <div class="row g-4">
            <?php if (mysqli_num_rows($all_courses_result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($all_courses_result)): ?>
                    <div class="col-sm-6 col-lg-4">
                        <div class="course-card h-100">
                            <?php if (!empty($course['image'])): ?>
                                <div class="course-image-wrapper">
                                    <img src="assets/uploads/courses/<?php echo htmlspecialchars($course['image']); ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($course['name']); ?>">
                                </div>
                            <?php endif; ?>
                            <div class="course-card-body">
                                <h3 class="course-name"><?php echo htmlspecialchars($course['name']); ?></h3>
                                <p class="course-description">
                                    <?php echo substr(strip_tags($course['description']), 0, 100); ?>...
                                </p>
                                <div class="course-meta">
                                    <div class="course-duration">
                                        <i class="fas fa-clock"></i>
                                        <?php echo htmlspecialchars($course['duration']); ?>
                                    </div>
                                    <div class="course-fee">
                                        <i class="fas fa-tag"></i>
                                        Rs. <?php echo number_format($course['fee']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="course-footer">
                                <a href="courses-single.php?id=<?php echo $course['id']; ?>" 
                                   class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>No courses found.
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Course pagination" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo ($page - 1); ?>">
                                <i class="fas fa-chevron-left"></i>
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
                            <a class="page-link" href="?page=<?php echo ($page + 1); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 0.5rem;
}

.stat-item h3 {
    font-size: 2rem;
    font-weight: 700;
}

.course-slider {
    position: relative;
    overflow: hidden;
    padding: 2rem 0;
}

.course-slider-inner {
    display: flex;
    gap: 1.5rem;
    animation: slide 30s linear infinite;
}

.course-slider-inner:hover {
    animation-play-state: paused;
}

@keyframes slide {
    0% { transform: translateX(0); }
    100% { transform: translateX(calc(-300px * 5)); }
}

.featured-course-card {
    min-width: 300px;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    overflow: hidden;
}

.featured-course-card:hover {
    transform: translateY(-5px);
}

.course-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    background: white;
    overflow: hidden;
}

.course-card:hover {
    transform: translateY(-5px);
}

.course-image-wrapper {
    height: 200px;
    overflow: hidden;
}

.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.course-card:hover .card-img-top {
    transform: scale(1.05);
}

.course-card-body {
    padding: 1.5rem;
}

.course-name {
    font-size: 1.25rem;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-weight: 600;
}

.course-description {
    color: #6c757d;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.course-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: #6c757d;
    font-size: 0.9rem;
}

.course-duration, .course-fee {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.course-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid rgba(0,0,0,0.1);
    background: rgba(0,0,0,0.02);
}

.section-title {
    position: relative;
    margin-bottom: 2rem;
}

.section-title::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -1rem;
    transform: translateX(-50%);
    width: 50px;
    height: 3px;
    background: #0d6efd;
}

@media (max-width: 768px) {
    .stat-item {
        padding: 0.5rem;
    }
    .stat-item h3 {
        font-size: 1.5rem;
    }
    .course-name {
        font-size: 1.1rem;
    }
}
</style>

<script>
// Simple course search functionality
document.getElementById('courseSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const courseCards = document.querySelectorAll('.course-card');
    
    courseCards.forEach(card => {
        const courseName = card.querySelector('.course-name').textContent.toLowerCase();
        const courseDesc = card.querySelector('.course-description').textContent.toLowerCase();
        
        if (courseName.includes(searchTerm) || courseDesc.includes(searchTerm)) {
            card.closest('.col-sm-6').style.display = '';
        } else {
            card.closest('.col-sm-6').style.display = 'none';
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
