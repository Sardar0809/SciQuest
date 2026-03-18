<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];
$apps = $pdo->prepare("SELECT a.*, t.title as task_title, t.budget, t.status as task_status, u.name as poster_name FROM applications a JOIN tasks t ON a.task_id = t.id JOIN users u ON t.user_id = u.id WHERE a.user_id = ? ORDER BY a.applied_at DESC");
$apps->execute([$user_id]);
$apps = $apps->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<h2>My Applications</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Task</th>
            <th>Posted by</th>
            <th>Budget</th>
            <th>Status</th>
            <th>Applied</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($apps as $app): ?>
        <tr>
            <td><a href="task.php?id=<?= $app['task_id'] ?>"><?= escape($app['task_title']) ?></a></td>
            <td><?= escape($app['poster_name']) ?></td>
            <td>Rs. <?= $app['budget'] ?></td>
            <td><?= $app['status'] ?></td>
            <td><?= timeAgo($app['applied_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include 'includes/footer.php'; ?>