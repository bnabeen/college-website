<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get total stats
$total_faculty = get_count($conn, 'faculty');
$total_courses = get_count($conn, 'courses');
$total_students = 1000; // Example static number, replace with actual count if available

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="section-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 position-relative z-1">
                <h1 class="display-4 fw-bold mb-4">About Our College</h1>
                <p class="lead mb-0">Learn about our history, mission, and commitment to providing quality education in Nepal.</p>
            </div>
        </div>
    </div>
    <div class="position-absolute top-0 end-0 w-50 h-100 d-none d-lg-block bg-overlay"></div>
</div>

<!-- Vision & Mission Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="custom-card h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                            <i class="fas fa-eye fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Our Vision</h3>
                        <p class="text-secondary mb-0">To be a leading institution in technical education, fostering innovation, research, and professional excellence while contributing to the technological advancement of Nepal.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="custom-card h-100">
                    <div class="card-body p-4">
                        <div class="feature-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                            <i class="fas fa-bullseye fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 mb-3">Our Mission</h3>
                        <p class="text-secondary mb-0">To provide quality technical education, promote research and development, and produce skilled professionals who can contribute to the nation's technological and economic growth.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="custom-card">
                    <div class="card-body p-4">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-user-tie fa-2x text-primary"></i>
                        </div>
                        <h3 class="display-4 fw-bold mb-2"><?php echo $total_faculty; ?>+</h3>
                        <p class="text-secondary mb-0">Expert Faculty Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-card">
                    <div class="card-body p-4">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                        </div>
                        <h3 class="display-4 fw-bold mb-2"><?php echo $total_courses; ?>+</h3>
                        <p class="text-secondary mb-0">Academic Programs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-card">
                    <div class="card-body p-4">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                        <h3 class="display-4 fw-bold mb-2"><?php echo $total_students; ?>+</h3>
                        <p class="text-secondary mb-0">Students Enrolled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- History Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="h1 mb-4">Our History</h2>
                <p class="text-secondary mb-4">Founded in 2010, Nepal College of Technology has been at the forefront of technical education in Nepal. Over the years, we have continuously evolved our curriculum and facilities to meet the changing demands of the technology sector.</p>
                <p class="text-secondary mb-4">Our commitment to excellence has helped us produce numerous successful professionals who are now working in leading organizations worldwide. We take pride in our state-of-the-art facilities, experienced faculty, and industry connections that provide our students with the best learning environment.</p>
                <div class="d-flex gap-3">
                    <a href="contact.php" class="btn btn-primary">
                        <i class="fas fa-envelope me-2"></i>Get in Touch
                    </a>
                    <a href="courses.php" class="btn btn-outline-primary">
                        <i class="fas fa-graduation-cap me-2"></i>Browse Courses
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="img-wrapper rounded-3 overflow-hidden">
                    <img src="assets/uploads/frontend-website/college-img.jpeg" alt="College History" class="img-fluid img-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">Our Core Values</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="custom-card h-100">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                            <i class="fas fa-star fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Excellence</h3>
                        <p class="text-secondary mb-0">Striving for the highest standards in everything we do.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-card h-100">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                            <i class="fas fa-lightbulb fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Innovation</h3>
                        <p class="text-secondary mb-0">Fostering creativity and embracing new technologies.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-card h-100">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                            <i class="fas fa-hands-helping fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Collaboration</h3>
                        <p class="text-secondary mb-0">Working together to achieve common goals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="custom-card h-100">
                    <div class="card-body p-4 text-center">
                        <div class="feature-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-4">
                            <i class="fas fa-balance-scale fa-2x text-primary"></i>
                        </div>
                        <h3 class="h5 mb-3">Integrity</h3>
                        <p class="text-secondary mb-0">Maintaining high ethical standards in all our actions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>