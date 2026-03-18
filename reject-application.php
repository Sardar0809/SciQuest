<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$app_id = $_GET['app_id'];
$stmt = $pdo->prepare("SELECT a.*, t.user_id as task_owner FROM applications a JOIN tasks t ON a.task_id = t.id WHERE a.id = ?");
$stmt->execute([$app_id]);
$app = $stmt->fetch();
if ($app && $app['task_owner'] == $_SESSION['user_id']) {
    $pdo->prepare("UPDATE applications SET status='rejected' WHERE id = ?")->execute([$app_id]);
}
redirect("task-applications.php?task_id=" . $app['task_id']);