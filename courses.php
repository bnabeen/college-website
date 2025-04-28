<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php'; // optional

// Fetch all courses
$all_courses_sql = "SELECT * FROM courses ORDER BY id DESC";
$all_courses_result = mysqli_query($conn, $all_courses_sql);

// Fetch featured courses (limit 3 or any flag like 'is_featured')
$featured_sql = "SELECT * FROM courses ORDER BY id DESC LIMIT 3";
$featured_result = mysqli_query($conn, $featured_sql);

include 'includes/header.php';
?>

<!-- Page Title -->
<section class="py-5 text-center fw-bold bg-light text-primary">
    <div class="container">
        <h1 class="mb-0">Our Courses</h1>
        <p class="lead">Explore our range of academic and skill-based programs</p>
    </div>                                                                          
</section>

<!-- Featured Courses -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4">Featured Courses</h2>
        <div class="row">
            <?php if (mysqli_num_rows($featured_result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($featured_result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($course['image'])): ?>
                                <img src="assets/uploads//<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                <p><?php echo substr(strip_tags($course['description']), 0, 100); ?>...</p>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="courses-single.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-block">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No featured courses available right now.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- All Courses -->
<section class="py-5">
    <div class="container">
        <h2 class="mb-4">📚 All Courses</h2>
        <div class="row">
            <?php if (mysqli_num_rows($all_courses_result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($all_courses_result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if (!empty($course['image'])): ?>
                                <img src="assets/uploads//<?php echo $course['image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['name']); ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                                <p><?php echo substr(strip_tags($course['description']), 0, 100); ?>...</p>
                                <span class="text-muted">Duration: <?php echo htmlspecialchars($course['duration']); ?></span>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="courses-single.php?id=<?php echo $course['id']; ?>" class="btn btn-outline-primary btn-block">Learn More</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning">No courses found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
