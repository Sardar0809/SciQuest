<?php require_once 'includes/config.php';

$top = $pdo->query("SELECT id, name, university, tasks_completed, rating_avg FROM users WHERE tasks_completed > 0 ORDER BY tasks_completed DESC, rating_avg DESC LIMIT 20");
$users = $top->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<h2>Leaderboard – Top Task Doers</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>University</th>
            <th>Tasks Completed</th>
            <th>Avg Rating</th>
        </tr>
    </thead>
    <tbody>
        <?php $rank=1; foreach ($users as $user): ?>
        <tr>
            <td><?= $rank++ ?></td>
            <td><a href="public-profile.php?id=<?= $user['id'] ?>"><?= escape($user['name']) ?></a></td>
            <td><?= escape($user['university']) ?></td>
            <td><?= $user['tasks_completed'] ?></td>
            <td><?= number_format($user['rating_avg'],1) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php include 'includes/footer.php'; ?>