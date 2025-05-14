<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = sanitize_input($conn, trim($_POST['name']));
    $email = sanitize_input($conn, trim($_POST['email']));
    $subject = sanitize_input($conn, trim($_POST['subject']));
    $message = sanitize_input($conn, trim($_POST['message']));
    
    // Validate input
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";
    
    if (empty($errors)) {
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message, status, created_at) VALUES (?, ?, ?, ?, 'unread', NOW())");
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
            // Clear form data after successful submission
            unset($_POST);
        } else {
            $errors[] = "Failed to send message. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
}

include 'includes/header.php';
?>

<!-- Hero Section -->
<div class="section-hero position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Get in Touch</h1>
                <p class="lead mb-4">Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                <div class="d-flex gap-3">
                    <a href="tel:+9771234567890" class="btn btn-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Call Us
                    </a>
                    <a href="mailto:info@nct.edu.np" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>Email Us
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="position-absolute top-0 end-0 w-50 h-100 d-none d-lg-block bg-overlay"></div>
</div>

<!-- Contact Section -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row g-4 justify-content-between">
            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="contact-info bg-light rounded-3 p-4 h-100 shadow-sm">
                    <h3 class="h4 text-primary mb-4">Contact Information</h3>
                    
                    <div class="contact-item d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="contact-icon bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="h6 mb-1">Our Location</h4>
                            <p class="mb-0">123 College Road<br>Kathmandu, Nepal</p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="contact-icon bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="h6 mb-1">Call Us</h4>
                            <p class="mb-0">
                                <a href="tel:+9771234567890" class="text-body text-decoration-none">+977 1234567890</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item d-flex mb-4">
                        <div class="flex-shrink-0">
                            <div class="contact-icon bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="h6 mb-1">Email Us</h4>
                            <p class="mb-0">
                                <a href="mailto:info@nct.edu.np" class="text-body text-decoration-none">info@nct.edu.np</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item d-flex">
                        <div class="flex-shrink-0">
                            <div class="contact-icon bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-primary"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="h6 mb-1">Working Hours</h4>
                            <p class="mb-0">Sunday - Friday<br>8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="card-title h4 mb-4">Send us a Message</h3>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle flex-shrink-0 me-2"></i>
                                <div>
                                    Thank you for your message! We'll get back to you soon.
                                </div>
                            </div>
                        <?php else: ?>
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="" class="row g-3 needs-validation" novalidate>
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                               required>
                                        <div class="invalid-feedback">Please enter your name</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                               required>
                                        <div class="invalid-feedback">Please enter a valid email</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control" id="subject" name="subject" 
                                               value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>" 
                                               required>
                                        <div class="invalid-feedback">Please enter a subject</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label">Message</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-comment"></i></span>
                                        <textarea class="form-control" id="message" name="message" rows="5" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                        <div class="invalid-feedback">Please enter your message</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="map-container" style="height: 450px;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.464269175623!2d85.30956331506207!3d27.704896982791853!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1911b8c9f41d%3A0x6a7b67b4969649d0!2sKathmandu%20Durbar%20Square!5e0!3m2!1sen!2snp!4v1689761234567!5m2!1sen!2snp" 
                        class="w-100 h-100" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include 'includes/footer.php'; ?>