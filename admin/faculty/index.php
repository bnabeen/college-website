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

// Get all faculty with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

$stmt = mysqli_prepare($conn, "SELECT * FROM faculty ORDER BY name ASC LIMIT ? OFFSET ?");
mysqli_stmt_bind_param($stmt, "ii", $per_page, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$faculty_members = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Get total count for pagination
$total_faculty = get_count($conn, 'faculty');
$total_pages = ceil($total_faculty / $per_page);

include '../../includes/admin-header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Manage Faculty</h2>
        <div>
            <a href="add.php" class="btn btn-primary rounded-pill px-4">+ Add New Faculty</a>
            <a href="../dashboard.php" class="btn btn-outline-primary rounded-pill px-4 ms-2">← Back</a>
        </div>
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
            <?php if (empty($faculty_members)): ?>
                <p class="text-muted text-center py-4">No faculty members found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#ID</th>
                                <th scope="col">Image</th>
                                <th scope="col">Name</th>
                                <th scope="col">Position</th>
                                <th scope="col">Department</th>
                                <th scope="col">Email</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($faculty_members as $faculty): ?>
                                <tr>
                                    <td><?php echo $faculty['id']; ?></td>
                                    <td>
                                        <?php if (!empty($faculty['image'])): ?>
                                            <img src="../../assets/images/faculty/<?php echo htmlspecialchars($faculty['image']); ?>" 
                                                alt="<?php echo htmlspecialchars($faculty['name']); ?>" 
                                                class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($faculty['name']); ?></td>
                                    <td><?php echo htmlspecialchars($faculty['position']); ?></td>
                                    <td><?php echo htmlspecialchars($faculty['department']); ?></td>
                                    <td><?php echo htmlspecialchars($faculty['email']); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo $faculty['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">Edit</a>
                                        <a href="delete.php?id=<?php echo $faculty['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1"
                                           onclick="return confirm('Are you sure you want to delete this faculty member?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Faculty pagination" class="mt-4">
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

<?php include '../../includes/admin-footer.php'; ?>