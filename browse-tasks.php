<?php require_once 'includes/config.php';

$category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'latest';

$query = "SELECT t.*, u.name as poster_name FROM tasks t JOIN users u ON t.user_id = u.id WHERE t.status='open'";
$params = [];

if ($category) {
    $query .= " AND t.category = ?";
    $params[] = $category;
}
if ($search) {
    $query .= " AND (t.title LIKE ? OR t.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($sort == 'budget_low') {
    $query .= " ORDER BY t.budget ASC";
} elseif ($sort == 'budget_high') {
    $query .= " ORDER BY t.budget DESC";
} elseif ($sort == 'deadline') {
    $query .= " ORDER BY t.deadline ASC";
} else {
    $query .= " ORDER BY t.created_at DESC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>
<?php include 'includes/header.php'; ?>
<h2>Browse Tasks</h2>
<form method="get" class="row g-3 mb-4">
    <div class="col-md-3">
        <select name="category" class="form-control">
            <option value="">All Categories</option>
            <option <?= $category=='Presentation'?'selected':'' ?>>Presentation</option>
            <option <?= $category=='Synopsis'?'selected':'' ?>>Synopsis</option>
            <option <?= $category=='Thesis'?'selected':'' ?>>Thesis</option>
            <option <?= $category=='Survey'?'selected':'' ?>>Survey</option>
            <option <?= $category=='Data Analysis'?'selected':'' ?>>Data Analysis</option>
            <option <?= $category=='Other'?'selected':'' ?>>Other</option>
        </select>
    </div>
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Search tasks..." value="<?= escape($search) ?>">
    </div>
    <div class="col-md-2">
        <select name="sort" class="form-control">
            <option value="latest" <?= $sort=='latest'?'selected':'' ?>>Latest</option>
            <option value="budget_low" <?= $sort=='budget_low'?'selected':'' ?>>Budget: Low to High</option>
            <option value="budget_high" <?= $sort=='budget_high'?'selected':'' ?>>Budget: High to Low</option>
            <option value="deadline" <?= $sort=='deadline'?'selected':'' ?>>Deadline (soonest)</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<div class="row">
    <?php foreach ($tasks as $task): ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><?= escape($task['title']) ?></h5>
                <p class="card-text"><?= substr(escape($task['description']), 0, 100) ?>...</p>
                <p><strong>Category:</strong> <?= $task['category'] ?></p>
                <p><strong>Budget:</strong> Rs. <?= $task['budget'] ?></p>
                <p><strong>Deadline:</strong> <?= $task['deadline'] ?></p>
                <a href="task.php?id=<?= $task['id'] ?>" class="btn btn-primary btn-sm">View Details</a>
            </div>
            <div class="card-footer text-muted">
                Posted by <?= escape($task['poster_name']) ?> • <?= timeAgo($task['created_at']) ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php include 'includes/footer.php'; ?>