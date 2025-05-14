<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME ?? 'NCT Admin'; ?> - Admin Panel</title>
      <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL ?? ''; ?>/admin/dashboard.php">
                <?php
                $user_id = $_SESSION['user_id'] ?? 0;
                $profile_query = mysqli_prepare($conn, "SELECT profile_image FROM users WHERE id = ?");
                mysqli_stmt_bind_param($profile_query, "i", $user_id);
                mysqli_stmt_execute($profile_query);
                $result = mysqli_stmt_get_result($profile_query);
                $user = mysqli_fetch_assoc($result);
                $profile_image = $user['profile_image'] ?? 'default-avatar.png';
                mysqli_stmt_close($profile_query);
                ?>
                <span class="fw-bold text-primary">NCT Admin</span>
            </a>
          
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#adminNavbar" aria-controls="adminNavbar" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'blogs/index.php') ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL ?? ''; ?>/admin/blogs/index.php">
                           Blogs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'course/index.php') ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL ?? ''; ?>/admin/courses/index.php">
                           Courses
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'faculty/index.php') ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL ?? ''; ?>/admin/faculty/index.php">
                            Faculty
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'gallery/index.php') ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL ?? ''; ?>/admin/gallery/index.php">
                            Gallery
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'messages/index.php') ? 'active' : ''; ?>" 
                           href="<?php echo BASE_URL ?? ''; ?>/admin/messages/index.php">
                            Messages
                        </a>
                </ul>

                <div class="dropdown">
                    <button class="btn btn-dark dropdown-toggle d-flex align-items-center" type="button" 
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        // Get user's profile image
                        $user_id = $_SESSION['user_id'] ?? 0;
                        $profile_query = mysqli_prepare($conn, "SELECT profile_image FROM users WHERE id = ?");
                        mysqli_stmt_bind_param($profile_query, "i", $user_id);
                        mysqli_stmt_execute($profile_query);
                        $result = mysqli_stmt_get_result($profile_query);
                        $user = mysqli_fetch_assoc($result);
                        $profile_image = $user['profile_image'] ?? 'default-avatar.png';
                        mysqli_stmt_close($profile_query);
                        ?>
                        <img src="<?php echo BASE_URL . '/assets/uploads//admin/' . htmlspecialchars($profile_image); ?>" 
                             alt="<?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>" 
                             class="rounded-circle me-2" 
                             style="width: 32px; height: 32px; object-fit: cover;">
                        <span class="d-none d-sm-inline">
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL ?? ''; ?>/admin/profile.php">
                                <i class="fas fa-user-circle me-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?php echo BASE_URL ?? ''; ?>/admin/settings.php">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?php echo BASE_URL ?? ''; ?>/auth/logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_SESSION['message_type']); ?> alert-dismissible fade show m-3" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['message']);
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>