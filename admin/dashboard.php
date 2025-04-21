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

<!-- Custom CSS for admin dashboard -->
<style>
    .dashboard-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 10px;
        overflow: hidden;
        border: none;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-icon {
        font-size: 2.5rem;
        margin-bottom: 10px;
        color: #4a6cf7;
    }
    
    .admin-sidebar {
        background-color: #f8f9fa;
        border-right: 1px solid #e9ecef;
        min-height: calc(100vh - 60px);
        padding-top: 20px;
    }
    
    .admin-content {
        padding: 30px;
    }
    
    .profile-dropdown .dropdown-toggle::after {
        display: none;
    }
    
    .profile-dropdown .dropdown-menu {
        min-width: 200px;
        padding: 15px;
    }
    
    .profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #4a6cf7;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .stats-number {
        font-size: 2.2rem;
        font-weight: 600;
        color: #333;
    }
    
    .stats-label {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .side-panel-heading {
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 15px;
        font-size: 1.25rem;
    }
</style>

<!-- Admin Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="blogs/">Blogs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="courses/">Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="faculty/">Faculty</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery/">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="messages/">Messages</a>
                </li>
            </ul>
            
            <!-- Profile Icon with Dropdown -->
            <div class="dropdown profile-dropdown">
                <button class="btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <span class="ms-2 d-none d-lg-inline"><?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="profileDropdown">
                    <li class="dropdown-item text-center">
                        <div class="profile-avatar mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                        </div>
                        <h6 class="mb-0"><?php echo htmlspecialchars($user['username']); ?></h6>
                        <small class="text-muted">Administrator</small>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-fill me-2"></i>My Profile</a></li>
                    <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Left Sidebar -->
        <div class="col-lg-3 col-xl-2 admin-sidebar d-none d-lg-block">
            <h6 class="side-panel-heading">Quick Access</h6>
            <div class="list-group list-group-flush mb-4">
                <a href="blogs/add.php" class="list-group-item list-group-item-action border-0 bg-transparent">
                    <i class="bi bi-file-earmark-plus me-2"></i>New Blog Post
                </a>
                <a href="courses/create.php" class="list-group-item list-group-item-action border-0 bg-transparent">
                    <i class="bi bi-mortarboard me-2"></i>Add Course
                </a>
                <a href="faculty/create.php" class="list-group-item list-group-item-action border-0 bg-transparent">
                    <i class="bi bi-person-plus me-2"></i>Add Faculty
                </a>
                <a href="gallery/upload.php" class="list-group-item list-group-item-action border-0 bg-transparent">
                    <i class="bi bi-upload me-2"></i>Upload Images
                </a>
            </div>
            
            <!-- Recent Blog Posts -->
            <h6 class="side-panel-heading">Recent Blog Posts</h6>
            <div class="recent-blogs mb-4">
                <?php
                $recent_blogs = get_recent_items($conn, 'blogs', 5);
                if (!empty($recent_blogs)) {
                    foreach ($recent_blogs as $blog) {
                        echo '<div class="mb-2 text-truncate">';
                        echo '<a href="blogs/edit.php?id=' . $blog['id'] . '" class="text-decoration-none text-secondary">';
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
                $recent_messages = get_recent_items($conn, 'contact_messages', 5);
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
                            <a href="courses/" class="btn btn-sm btn-outline-primary rounded-pill px-4">Manage</a>
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
                
                <!-- Admin Users (Placeholder) -->
                <div class="col-md-4 col-xl-2">
                    <div class="card dashboard-card h-100 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-person-badge card-icon"></i>
                            <div class="stats-number">1</div>
                            <p class="stats-label">Admin Users</p>
                        </div>
                        <div class="card-footer bg-white border-0 text-center pb-3">
                            <a href="users/" class="btn btn-sm btn-outline-primary rounded-pill px-4">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
         
<div class="fixed-bottom"><?php include '../includes/admin-footer.php'; ?></div>