<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Start session and verify user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

// Get counts for various sections
$blog_count = get_count($conn, 'blogs');
$course_count = get_count($conn, 'courses');
$faculty_count = get_count($conn, 'faculty');
$unread_messages = get_count($conn, 'contact_messages', "status = 'unread'");
$gallery_count = get_count($conn, 'gallery');

// Get user information
$user_id = $_SESSION['user_id'];
$user = get_user_by_id($conn, $user_id);

include '../includes/admin-header.php';
?>

<div class="container py-5">
    <h1 class="mb-4">Admin Dashboard</h1>
    <p class="lead">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</p>

    <div class="row g-4 mb-5">
        <!-- Blog Posts -->
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-6"><?php echo $blog_count; ?></h2>
                    <p class="card-text">Blog Posts</p>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="blogs/" class="text-decoration-none">Manage Blogs</a>
                </div>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-6"><?php echo $course_count; ?></h2>
                    <p class="card-text">Courses</p>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="courses/" class="text-decoration-none">Manage Courses</a>
                </div>
            </div>
        </div>

        <!-- Faculty Members -->
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-6"><?php echo $faculty_count; ?></h2>
                    <p class="card-text">Faculty Members</p>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="faculty/" class="text-decoration-none">Manage Faculty</a>
                </div>
            </div>
        </div>

        <!-- Unread Messages -->
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-6"><?php echo $unread_messages; ?></h2>
                    <p class="card-text">Unread Messages</p>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="messages/" class="text-decoration-none">View Messages</a>
                </div>
            </div>
        </div>

        <!-- Gallery Images -->
        <div class="col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="display-6"><?php echo $gallery_count; ?></h2>
                    <p class="card-text">Gallery Images</p>
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="gallery/" class="text-decoration-none">Manage Gallery</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Blog Posts & Messages -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recent Blog Posts</h5>
                </div>
                <div class="card-body">
                    <?php
                    $recent_blogs = get_recent_items($conn, 'blogs', 5);
                    if (!empty($recent_blogs)) {
                        echo '<ul class="list-group">';
                        foreach ($recent_blogs as $blog) {
                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                            echo htmlspecialchars($blog['title']);
                            echo '<span class="badge bg-primary rounded-pill">' . format_date($blog['created_at']) . '</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p class="text-muted">No blog posts yet.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Recent Messages</h5>
                </div>
                <div class="card-body">
                    <?php
                    $recent_messages = get_recent_items($conn, 'contact_messages', 5);
                    if (!empty($recent_messages)) {
                        echo '<ul class="list-group">';
                        foreach ($recent_messages as $message) {
                            $class = $message['status'] == 'unread' ? 'list-group-item list-group-item-info' : 'list-group-item';
                            echo '<li class="' . $class . '">';
                            echo '<strong>' . htmlspecialchars($message['name']) . '</strong>: ';
                            echo htmlspecialchars(substr($message['message'], 0, 50)) . '...';
                            echo '<span class="float-end text-muted">' . format_date($message['created_at']) . '</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<p class="text-muted">No messages yet.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/admin-footer.php'; ?>