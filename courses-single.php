<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to courses page if ID is not valid
    header("Location: courses.php");
    exit;
}

$course_id = intval($_GET['id']);

// Fetch course details
$stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
$stmt->close();

if (!$course) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Course not found.</div></div>";
    include 'includes/footer.php';
    exit;
}

include 'includes/header.php';
?>

<!-- Course Details -->
<section class="course-detail-section py-5">
    <div class="container">
        <div class="row">
            <!-- Course Image -->
            <div class="col-md-6 mb-4">
                <?php if (!empty($course['image'])): ?>
                    <img src="assets/uploads//<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>" class="img-fluid rounded shadow">
                <?php else: ?>
                    <img src="assets/uploads//mobile-app-development.png" alt="Image" class="img-fluid rounded shadow">
                <?php endif; ?>
            </div>

            <!-- Course Content -->
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($course['name']); ?></h1>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($course['duration']); ?></p>
                <p><strong>Fee:</strong> Rs. <?php echo number_format($course['fee'], 2); ?></p>
                <p><strong>Requirements:</strong> <?php echo nl2br(htmlspecialchars($course['requirements'])); ?></p>
                <p class="mt-4"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>

                <a href="contact.php" class="btn btn-primary mt-3">Enroll Now</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
