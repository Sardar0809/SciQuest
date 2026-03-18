<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$tasks = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
$tasks->execute([$user_id]);
$tasks = $tasks->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<h2>My Posted Tasks</h2>
<?php foreach ($tasks as $task): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5><?= escape($task['title']) ?></h5>
            <p>Status: <?= $task['status'] ?> | Budget: Rs. <?= $task['budget'] ?> | Deadline: <?= $task['deadline'] ?></p>
            <a href="task-applications.php?task_id=<?= $task['id'] ?>" class="btn btn-sm btn-info">View Applications</a>
            <?php if ($task['status'] == 'open'): ?>
                <a href="close-task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning">Close</a>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php include 'includes/footer.php'; ?>