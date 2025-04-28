<?php

require_once 'includes/config.php';
require_once 'includes/db.php';

// Fetch all faculty members from the database
$query = "SELECT * FROM faculty";
$result = mysqli_query($conn, $query);

$faculty_members = mysqli_num_rows($result) > 0 ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

include 'includes/header.php';
?>

<!-- Faculty List Section -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h1 class="mb-4">Meet Our Faculty</h1>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row g-4">
            <?php if (!empty($faculty_members)): ?>
                <?php foreach ($faculty_members as $faculty): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <img src="assets/uploads//<?= htmlspecialchars($faculty['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($faculty['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($faculty['name']) ?></h5>
                                <p class="card-text"><strong>Position:</strong> <?= htmlspecialchars($faculty['position']) ?></p>
                                <p class="card-text"><strong>Department:</strong> <?= htmlspecialchars($faculty['department']) ?></p>
                                <p class="card-text"><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($faculty['email']) ?>"><?= htmlspecialchars($faculty['email']) ?></a></p>
                                <p class="card-text"><strong>Bio:</strong> <?= nl2br(substr($faculty['bio'], 0, 100)) ?>...</p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No faculty members found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
