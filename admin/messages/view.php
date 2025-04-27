<!-- <?php
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = 'Invalid message ID';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$message_id = (int)$_GET['id'];

// Get message details
$stmt = mysqli_prepare($conn, "SELECT * FROM contact_messages WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $message_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    $_SESSION['message'] = 'Message not found';
    $_SESSION['message_type'] = 'danger';
    header('Location: index.php');
    exit;
}

$message = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Mark message as read if it's unread
if ($message['status'] === 'unread') {
    $update_stmt = mysqli_prepare($conn, "UPDATE contact_messages SET status = 'read' WHERE id = ?");
    mysqli_stmt_bind_param($update_stmt, "i", $message_id);
    mysqli_stmt_execute($update_stmt);
    mysqli_stmt_close($update_stmt);
}

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary">View Message</h2>
                <a href="index.php" class="btn btn-outline-primary rounded-pill px-4">← Back to Messages</a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($message['subject']); ?></h5>
                            <p class="text-muted small mb-0">
                                From: <?php echo htmlspecialchars($message['name']); ?> 
                                (<a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                    <?php echo htmlspecialchars($message['email']); ?>
                                </a>)
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-<?php echo $message['status'] === 'unread' ? 'primary' : 'secondary'; ?>">
                                <?php echo ucfirst($message['status']); ?>
                            </span>
                            <p class="text-muted small mb-0 mt-1">
                                <?php echo format_date($message['created_at']); ?>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>" 
                           class="btn btn-primary rounded-pill px-4">
                            Reply via Email
                        </a>
                        <a href="delete.php?id=<?php echo $message['id']; ?>" 
                           class="btn btn-outline-danger rounded-pill px-4"
                           onclick="return confirm('Are you sure you want to delete this message?')">
                            Delete Message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/admin-footer.php'; ?> -->