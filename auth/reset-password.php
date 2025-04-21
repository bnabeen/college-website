<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);

    if (empty($username)) {
        $errors[] = "Username or email is required.";
    } else {
        $sql = "SELECT id, username FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {
            // Generate token and expiry
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $update_sql = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?";
            $update_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param($update_stmt, "ssi", $token, $expires, $user['id']);
            mysqli_stmt_execute($update_stmt);

            // Simulated email link
            $reset_link = "http://localhost/learn-php/college-website/auth/new_password.php?token=$token";

            // Normally you would send this via email
            $success = "A password reset link has been generated. <br><a href='$reset_link'>Click here to reset password</a>";

            mysqli_stmt_close($update_stmt);
        } else {
            $errors[] = "No account found with that username.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-form">
            <h2>Reset Password</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username or Email</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>
