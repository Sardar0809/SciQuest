<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$app_id = $_GET['app_id'];
// Verify this application belongs to the logged-in user and status is accepted
$stmt = $pdo->prepare("SELECT a.*, t.budget FROM applications a JOIN tasks t ON a.task_id = t.id WHERE a.id = ? AND a.user_id = ? AND a.status = 'accepted'");
$stmt->execute([$app_id, $_SESSION['user_id']]);
$app = $stmt->fetch();
if ($app) {
    // Update application status to completed
    $pdo->prepare("UPDATE applications SET status='completed' WHERE id = ?")->execute([$app_id]);
    // Update task status to completed
    $pdo->prepare("UPDATE tasks SET status='completed' WHERE id = ?")->execute([$app['task_id']]);
    // Increment doer's completed tasks and total earned
    $pdo->prepare("UPDATE users SET tasks_completed = tasks_completed + 1, total_earned = total_earned + ? WHERE id = ?")->execute([$app['budget'], $_SESSION['user_id']]);
    // Redirect to rating page
    redirect("rate.php?task_id=" . $app['task_id'] . "&doer_id=" . $_SESSION['user_id']);
}
redirect("my-applications.php");