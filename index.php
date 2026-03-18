<?php require_once 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="jumbotron bg-light p-5 rounded">
    <h1 class="display-4">Welcome to SciTasker</h1>
    <p class="lead">A free platform for Pakistani science students to post assignments and get help from peers.</p>
    <hr class="my-4">
    <p>Post a task, set your budget, and let others apply. No fees, just collaboration.</p>
    <?php if (!isLoggedIn()): ?>
        <a class="btn btn-primary btn-lg" href="register.php" role="button">Get Started</a>
    <?php else: ?>
        <a class="btn btn-primary btn-lg" href="post-task.php" role="button">Post a Task</a>
        <a class="btn btn-secondary btn-lg" href="browse-tasks.php" role="button">Browse Tasks</a>
    <?php endif; ?>
</div>

<h2 class="mt-5">Recent Tasks</h2>
<div class="row">
    <?php
    $stmt = $pdo->query("SELECT t.*, u.name as poster_name FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.status='open' ORDER BY t.created_at DESC LIMIT 6");
    while($task = $stmt->fetch()):
    ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?= escape($task['title']) ?></h5>
                <p class="card-text"><?= substr(escape($task['description']), 0, 100) ?>...</p>
                <p class="text-muted">Budget: Rs. <?= $task['budget'] ?></p>
                <p class="text-muted">Deadline: <?= $task['deadline'] ?></p>
                <a href="task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-primary">View Details</a>
            </div>
            <div class="card-footer text-muted">
                Posted by <?= escape($task['poster_name']) ?> • <?= timeAgo($task['created_at']) ?>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>