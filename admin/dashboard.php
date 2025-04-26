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

$page_title = "Admin Dashboard";
include '../includes/admin-header.php';

?>


<div>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-lg-2 col-xl-2 admin-sidebar d-none d-lg-block">
                <h6 class="side-panel-heading">Quick Access</h6>
                <div class="list-group list-group-flush mb-4">
                    <a href="blogs/add.php" class="list-group-item list-group-item-action border-0 bg-transparent"> Add Blog </a>
                    <a href="courses/add.php" class="list-group-item list-group-item-action border-0 bg-transparent"> Add Course </a>
                    <a href="faculty/add.php" class="list-group-item list-group-item-action border-0 bg-transparent"> Add Faculty </a>
                    <a href="gallery/upload.php" class="list-group-item list-group-item-action border-0 bg-transparent">Upload Images </a>
                </div>
                
                <!-- Recent Blog Posts -->
                <h6 class="side-panel-heading">Recent Blog Posts</h6>
                <div class="recent-blogs mb-4">
                    <?php
                    $recent_blogs = get_recent_items($conn, 'blogs', 3);
                    if (!empty($recent_blogs)) {
                        foreach ($recent_blogs as $blog) {
                            echo '<div class="mb-2 text-truncate">';
                            echo '<a href="../blog-single.php?id=' . $blog['id'] . '" class="text-decoration-none text-secondary">';
                            echo '<small><i class="bi bi-file-earmark-text me-1"></i>';
                            echo htmlspecialchars($blog['title']) . '</small>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted small">No blog posts yet.</p>';
                    }
                    ?>
                </div>
                
                <!-- Recent Messages -->
                <h6 class="side-panel-heading">Recent Messages</h6>
                <div class="recent-messages">
                    <?php
                    $recent_messages = get_recent_items($conn, 'contact_messages', 3);
                    if (!empty($recent_messages)) {
                        foreach ($recent_messages as $message) {
                            $class = $message['status'] == 'unread' ? 'text-primary fw-bold' : 'text-secondary';
                            echo '<div class="mb-2 text-truncate">';
                            echo '<a href="messages/view.php?id=' . $message['id'] . '" class="text-decoration-none ' . $class . '">';
                            echo '<small><i class="bi bi-envelope me-1"></i>';
                            echo htmlspecialchars($message['name']) . '</small>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted small">No messages yet.</p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <div class="col-lg-9 col-xl-10 admin-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">Admin Dashboard</h1>
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2"><?php echo date('l, F j, Y'); ?></span>
                    </div>
                </div>
                
                <!-- Dashboard Stats Cards -->
                <div class="row g-4 mb-5">
                    <!-- Blog Posts -->
                    <div class="col-md-4 col-xl-2">
                        <div class="card dashboard-card h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-file-earmark-text card-icon"></i>
                                <div class="stats-number"><?php echo $blog_count; ?></div>
                                <p class="stats-label">Blog Posts</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="blogs/" class="btn btn-sm btn-outline-primary rounded-pill px-4">Manage</a>
                            </div>
                        </div>
                    </div>

                    <!-- Courses -->
                    <div class="col-md-4 col-xl-2">
                        <div class="card dashboard-card h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-mortarboard card-icon"></i>
                                <div class="stats-number"><?php echo $course_count; ?></div>
                                <p class="stats-label">Courses</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="courses/index.php" class="btn btn-sm btn-outline-primary rounded-pill px-4">Manage</a>
                            </div>
                        </div>
                    </div>

                    <!-- Faculty Members -->
                    <div class="col-md-4 col-xl-2">
                        <div class="card dashboard-card h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-people card-icon"></i>
                                <div class="stats-number"><?php echo $faculty_count; ?></div>
                                <p class="stats-label">Faculty</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="faculty/" class="btn btn-sm btn-outline-primary rounded-pill px-4">Manage</a>
                            </div>
                        </div>
                    </div>

                    <!-- Unread Messages -->
                    <div class="col-md-4 col-xl-2">
                        <div class="card dashboard-card h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-envelope card-icon"></i>
                                <div class="stats-number"><?php echo $unread_messages; ?></div>
                                <p class="stats-label">Unread Messages</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="messages/" class="btn btn-sm btn-outline-primary rounded-pill px-4">View</a>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    <div class="col-md-4 col-xl-2">
                        <div class="card dashboard-card h-100 shadow-sm">
                            <div class="card-body text-center p-4">
                                <i class="bi bi-images card-icon"></i>
                                <div class="stats-number"><?php echo $gallery_count; ?></div>
                                <p class="stats-label">Gallery Images</p>
                            </div>
                            <div class="card-footer bg-white border-0 text-center pb-3">
                                <a href="gallery/" class="btn btn-sm btn-outline-primary rounded-pill px-4">Manage</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div><?php include '../includes/admin-footer.php'; ?></div>