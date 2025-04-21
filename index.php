<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get featured courses 
$featured_courses = [];
$sql_courses = "SELECT * FROM courses ORDER BY id DESC LIMIT 3";
$result_courses = mysqli_query($conn, $sql_courses);
if ($result_courses && mysqli_num_rows($result_courses) > 0) {
    while ($row = mysqli_fetch_assoc($result_courses)) {
        $featured_courses[] = $row;
    }
}

// Get recent blog posts (Procedural)
$recent_blogs = [];
$sql_blogs = "SELECT * FROM blogs WHERE status = 'published' ORDER BY created_at DESC LIMIT 3";
$result_blogs = mysqli_query($conn, $sql_blogs);
if ($result_blogs && mysqli_num_rows($result_blogs) > 0) {
    while ($row = mysqli_fetch_assoc($result_blogs)) {
        $recent_blogs[] = $row;
    }
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Welcome to Nepal College of Technology</h1>
                <p class="lead">Empowering students with quality education and practical skills for the future.</p>
                <div class="mt-4">
                    <a href="courses.php" class="btn btn-primary mr-2">Explore Courses</a>
                    <a href="contact.php" class="btn btn-outline-primary">Contact Us</a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="assets/images/campus.jpg" alt="Nepal College of Technology" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Why Choose Us</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-graduation-cap fa-3x mb-3 text-primary"></i>
                        <h3>Quality Education</h3>
                        <p>We provide top-notch education with modern teaching methodologies and experienced faculty.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-laptop-code fa-3x mb-3 text-primary"></i>
                        <h3>Modern Facilities</h3>
                        <p>Our campus is equipped with state-of-the-art labs, libraries, and digital classrooms.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-3x mb-3 text-primary"></i>
                        <h3>Career Opportunities</h3>
                        <p>We offer excellent placement opportunities and career guidance to all our students.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses Section -->
<section class="courses-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Courses</h2>
            <a href="courses.php" class="btn btn-outline-primary">View All</a>
        </div>

        <div class="row">
            <?php if (!empty($featured_courses)): ?>
                <?php foreach ($featured_courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($course['image'])): ?>
                                <img src="assets/images/<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($course['name']); ?></h3>
                                <p class="card-text"><?php echo substr(strip_tags($course['description']), 0, 100); ?>...</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-muted">Duration: <?php echo htmlspecialchars($course['duration']); ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="courses-single.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-block">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No courses available at the moment.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Recent Blog Posts Section -->
<section class="blogs-section py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Recent News & Events</h2>
            <a href="blogs.php" class="btn btn-outline-primary">View All</a>
        </div>

        <div class="row">
            <?php if (!empty($recent_blogs)): ?>
                <?php foreach ($recent_blogs as $blog): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($blog['image'])): ?>
                                <img src="assets/images/<?php echo $blog['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                <p class="card-text"><?php echo substr(strip_tags($blog['content']), 0, 150); ?>...</p>
                            </div>
                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted"><?php echo format_date($blog['created_at']); ?></small>
                                    <a href="blog-single.php?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No blog posts available at the moment.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
