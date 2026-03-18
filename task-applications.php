<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$task_id = $_GET['task_id'] ?? 0;
$task = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$task->execute([$task_id, $_SESSION['user_id']]);
$task = $task->fetch();
if (!$task) redirect('my-tasks.php');

$applications = $pdo->prepare("SELECT a.*, u.name, u.university, u.course, u.year, u.skills, u.rating_avg FROM applications a JOIN users u ON a.user_id = u.id WHERE a.task_id = ? ORDER BY a.applied_at DESC");
$applications->execute([$task_id]);
$apps = $applications->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<h2>Applications for "<?= escape($task['title']) ?>"</h2>
<?php foreach ($apps as $app): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5><?= escape($app['name']) ?></h5>
            <p><?= escape($app['university']) ?> • <?= escape($app['course']) ?> • Year <?= escape($app['year']) ?></p>
            <p><strong>Proposal:</strong> <?= nl2br(escape($app['proposal'])) ?></p>
            <p><strong>Rating:</strong> <?= number_format($app['rating_avg'],1) ?> / 5</p>
            <?php if ($app['status'] == 'pending'): ?>
                <a href="accept-application.php?app_id=<?= $app['id'] ?>" class="btn btn-success btn-sm">Accept</a>
                <a href="reject-application.php?app_id=<?= $app['id'] ?>" class="btn btn-danger btn-sm">Reject</a>
            <?php else: ?>
                <span class="badge bg-info"><?= $app['status'] ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php include 'includes/footer.php'; ?>