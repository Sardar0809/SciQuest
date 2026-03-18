<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Get recent tasks posted
$tasks = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$tasks->execute([$user_id]);
$recent_tasks = $tasks->fetchAll();

// Get completed tasks as doer
$completed = $pdo->prepare("SELECT t.*, u.name as poster_name FROM tasks t JOIN applications a ON t.id = a.task_id JOIN users u ON t.user_id = u.id WHERE a.user_id = ? AND a.status = 'completed' ORDER BY a.applied_at DESC LIMIT 5");
$completed->execute([$user_id]);
$completed_tasks = $completed->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <img src="assets/uploads/avatars/<?= $user['avatar'] ?: 'default.png' ?>" class="card-img-top" alt="Avatar">
            <div class="card-body">
                <h5 class="card-title"><?= escape($user['name']) ?></h5>
                <p class="card-text"><?= escape($user['university']) ?> • <?= escape($user['course']) ?> • Year <?= escape($user['year']) ?></p>
                <p><strong>Bio:</strong> <?= nl2br(escape($user['bio'])) ?></p>
                <p><strong>Skills:</strong> <?= escape($user['skills']) ?></p>
                <p><strong>Tasks Completed:</strong> <?= $user['tasks_completed'] ?></p>
                <p><strong>Average Rating:</strong> <?= number_format($user['rating_avg'], 1) ?> / 5</p>
                <p><strong>Total Earned:</strong> Rs. <?= $user['total_earned'] ?></p>
                <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <ul class="nav nav-tabs" id="profileTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#posted">Posted Tasks</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#completed">Completed Tasks</a></li>
        </ul>
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="posted">
                <?php foreach ($recent_tasks as $task): ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6><?= escape($task['title']) ?></h6>
                            <p>Status: <?= $task['status'] ?> | Budget: Rs. <?= $task['budget'] ?></p>
                            <a href="task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-info">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="tab-pane fade" id="completed">
                <?php foreach ($completed_tasks as $task): ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h6><?= escape($task['title']) ?></h6>
                            <p>For: <?= escape($task['poster_name']) ?> | Earned: Rs. <?= $task['budget'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>