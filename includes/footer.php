<footer class="footer bg-dark text-white pt-5 pb-3">
    <div class="container">
        <!-- Main Footer Content -->
        <div class="row g-4">
            <!-- About Column -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-content pe-lg-4">
                    <div class="mb-4">
                        <img src="<?php echo BASE_URL ?? ''; ?>/assets/uploads//logo-white.png" alt="NCT Logo" height="40">
                    </div>
                    <p class="text-muted mb-4">We are a leading institution in Nepal dedicated to providing quality education and practical skills for the future.</p>
                    <div class="social-links">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle me-2">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white mb-4">Quick Links</h5>
                <ul class="list-unstyled footer-links">
                    <li><a href="<?php echo BASE_URL ?? ''; ?>/index.php">Home</a></li>
                    <li><a href="<?php echo BASE_URL ?? ''; ?>/about.php">About Us</a></li>
                    <li><a href="<?php echo BASE_URL ?? ''; ?>/courses.php">Courses</a></li>
                    <li><a href="<?php echo BASE_URL ?? ''; ?>/faculty.php">Faculty</a></li>
                    <li><a href="<?php echo BASE_URL ?? ''; ?>/gallery.php">Gallery</a></li>
                    <li><a href="<?php echo BASE_URL ?? ''; ?>/contact.php">Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-4">Contact Info</h5>
                <ul class="list-unstyled footer-info">
                    <li class="d-flex mb-3">
                        <i class="fas fa-map-marker-alt text-primary mt-1 me-3"></i>
                        <span>123 College Road<br>Kathmandu, Nepal</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="fas fa-phone-alt text-primary mt-1 me-3"></i>
                        <span>+977 1234567890</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="fas fa-envelope text-primary mt-1 me-3"></i>
                        <span>info@nct.edu.np</span>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-white mb-4">Newsletter</h5>
                <p class="text-white mb-4">Subscribe to our newsletter for updates and news.</p>
                <form class="newsletter-form">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control bg-dark border-secondary text-white" 
                               placeholder="Your email" aria-label="Your email">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Divider -->
        <hr class="my-4 border-secondary">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-muted mb-md-0">
                    &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME ?? 'Nepal College of Technology'; ?>. 
                    All Rights Reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="<?php echo BASE_URL ?? ''; ?>/privacy.php" class="text-muted">Privacy Policy</a>
                    </li>
                    <li class="list-inline-item">
                        <span class="text-muted mx-2">|</span>
                    </li>
                    <li class="list-inline-item">
                        <a href="<?php echo BASE_URL ?? ''; ?>/terms.php" class="text-muted">Terms of Use</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?php echo BASE_URL ?? ''; ?>/assets/js/main.js"></script>
</body>
</html>
