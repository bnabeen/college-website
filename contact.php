<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$errors = [];
$success = false;

// Process contact form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    // Validate input
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // Proceed if no validation errors
    if (empty($errors)) {
        // Prepare SQL statement
        $stmt = mysqli_prepare($conn, "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        my_sqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);
        
        // Execute statement
        if (mysqli_stmt_execute($stmt)) {
            $success = true;
        } else {
            $errors[] = "Failed to send message: " . $mysqli_connect_error();
        }
        
        mysqli_close($stmt);
    }
}

include 'includes/header.php';
?>

<!-- Page Header -->
<div class="page-header bg-primary text-white">
    <div class="container">
        <h1>Contact Us</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Contact Us</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Contact Section -->
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Get in Touch</h2>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Your message has been sent successfully! We will get back to you soon.
                            </div>
                        <?php else: ?>
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Send Message</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">Contact Information</h2>
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                                123 College Road, Kathmandu, Nepal
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-phone mr-2 text-primary"></i>
                                +977 1234567890
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-envelope mr-2 text-primary"></i>
                                info@nepalcollegeoftech.edu.np
                            </li>
                            <li>
                                <i class="fas fa-clock mr-2 text-primary"></i>
                                Monday - Friday: 8:00 AM - 5:00 PM
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body p-0">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3531.464269175623!2d85.30956331506207!3d27.704896982791853!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb1911b8c9f41d%3A0x6a7b67b4969649d0!2sKathmandu%20Durbar%20Square!5e0!3m2!1sen!2snp!4v1689761234567!5m2!1sen!2snp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>