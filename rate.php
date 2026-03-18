<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $doer_id = $_POST['doer_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];
    $rater_id = $_SESSION['user_id'];

    // Verify that the rater is the task owner and task is completed
    $check = $pdo->prepare("SELECT t.user_id as poster_id FROM tasks t WHERE t.id = ? AND t.status='completed'");
    $check->execute([$task_id]);
    $task = $check->fetch();
    if ($task && $task['poster_id'] == $rater_id) {
        $pdo->prepare("INSERT INTO ratings (task_id, rater_id, ratee_id, rating, comment) VALUES (?,?,?,?,?)")
            ->execute([$task_id, $rater_id, $doer_id, $rating, $comment]);
        // Update doer's average rating
        $avg = $pdo->prepare("SELECT AVG(rating) as avg FROM ratings WHERE ratee_id = ?");
        $avg->execute([$doer_id]);
        $avgRating = $avg->fetch()['avg'];
        $pdo->prepare("UPDATE users SET rating_avg = ? WHERE id = ?")->execute([$avgRating, $doer_id]);
        redirect("task.php?id=$task_id");
    }
}

$task_id = $_GET['task_id'];
$doer_id = $_GET['doer_id'];
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Rate the Task Doer</div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="task_id" value="<?= $task_id ?>">
                    <input type="hidden" name="doer_id" value="<?= $doer_id ?>">
                    <div class="mb-3">
                        <label>Rating (1-5)</label>
                        <select name="rating" class="form-control">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Below Average</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Comment (optional)</label>
                        <textarea name="comment" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Rating</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>