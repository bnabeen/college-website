<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone
date_default_timezone_set('Asia/Kathmandu');

// Define base URL (for links, images, etc.)
define('BASE_URL', 'http://localhost/learn-php/college-website');

// Error reporting (for development, turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include other global settings if needed
define('SITE_NAME', 'Nepal College of Technology');
define('SITE_SHORT_NAME', 'NCT');
define('ADMIN_EMAIL', 'ekbazzarnepal@gmail.com');

?>
