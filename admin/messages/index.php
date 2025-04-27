<?php
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

// Get all messages with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$stmt = mysqli_prepare($conn, "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get total count for pagination
$total_messages = get_count($conn, 'contact_messages');
$total_pages = ceil($total_messages / $per_page);

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Messages</h2>
        <a href="../dashboard.php" class="btn btn-outline-primary rounded-pill px-4">← Back</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php 
                echo $_SESSION['message'];
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <?php if (empty($messages)): ?>
                <p class="text-muted text-center py-4">No messages found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Status</th>
                                <th scope="col">Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php foreach ($messages as $message): ?>
                                <tr class="<?php echo $message['status'] === 'unread' ? 'table-active' : ''; ?>">
                                    <td><?php echo $message['id']; ?></td>
                                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                            <?php echo htmlspecialchars($message['email']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $message['status'] === 'unread' ? 'primary' : 'secondary'; ?>">
                                            <?php echo ucfirst($message['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo format_date($message['created_at']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center p-2 view-message"  style="width: 40px; height: 40px;"
                                                    data-bs-toggle="modal" data-bs-target="#messageModal"
                                                    data-id="<?php echo $message['id']; ?>"
                                                    data-name="<?php echo htmlspecialchars($message['name']); ?>"
                                                    data-email="<?php echo htmlspecialchars($message['email']); ?>"
                                                    data-subject="<?php echo htmlspecialchars($message['subject']); ?>"
                                                    data-message="<?php echo htmlspecialchars($message['message']); ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="delete.php?id=<?php echo $message['id']; ?>" 
                                                class="btn btn-outline-danger rounded-circle d-flex align-items-center justify-content-center p-2"  style="width: 40px; height: 40px;"
                                                onclick="return confirm('Are you sure you want to delete this message?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Message pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="messageName"></span></p>
                <p><strong>Email:</strong> <span id="messageEmail"></span></p>
                <p><strong>Subject:</strong> <span id="messageSubject"></span></p>
                <p><strong>Message:</strong></p>
                <p id="messageContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary mark-as-read" data-id="">Mark as Read</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messageModal = document.getElementById('messageModal');
        messageModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const messageId = button.dataset.id;
            const messageName = button.dataset.name;
            const messageEmail = button.dataset.email;
            const messageSubject = button.dataset.subject;
            const messageContent = button.dataset.message;

            document.getElementById('messageName').innerText = messageName;
            document.getElementById('messageEmail').innerText = messageEmail;
            document.getElementById('messageSubject').innerText = messageSubject;
            document.getElementById('messageContent').innerText = messageContent;
            document.querySelector('.mark-as-read').dataset.id = messageId;
        });

        document.querySelector('.mark-as-read').addEventListener('click', function () {
            const messageId = this.dataset.id;
            fetch('mark_as_read.php?id=' + messageId)
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to mark as read');
                    }
                });
        });
    });
</script>

<?php include '../../includes/admin-footer.php'; ?>