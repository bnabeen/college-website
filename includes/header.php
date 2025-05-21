<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME ?? 'Nepal College of Technology'; ?></title>
    
    <!-- Meta tags for SEO -->
    <meta name="description" content="Nepal College of Technology - Leading institution for technical education in Nepal">
    <meta name="keywords" content="NCT, college, technology, education, nepal">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL ?? ''; ?>/assets/uploads//favicon.ico">
      <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">
    
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar bg-primary text-white py-2">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small>
                        <i class="fas fa-phone me-2"></i> +977 9863442548
                        <span class="mx-3">|</span>
                        <i class="fas fa-envelope me-2"></i> info@nct.edu.np
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <a href="https:/facebook.com/nabeen.me" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="https:/x.com/bnabeen_" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="https:/linkedin.com/in/nabeenbudha" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https:/instagram.com/nabeen.me" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo BASE_URL ?? ''; ?>/">
                <span class="fw-bold text-primary">NCT</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#adminNavbar" aria-controls="adminNavbar" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $_SERVER['PHP_SELF'] == '/index.php' ? 'active' : ''; ?>" href="<?php echo BASE_URL ?? ''; ?>/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'contact.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL ?? ''; ?>/blogs.php">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'courses.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL ?? ''; ?>/courses.php">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'faculty.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL ?? ''; ?>/faculty.php">Faculty</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'gallery.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL ?? ''; ?>/gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($_SERVER['PHP_SELF'], 'contact.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL ?? ''; ?>/contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>