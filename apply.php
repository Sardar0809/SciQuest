<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $proposal = $_POST['proposal'];
    $user_id = $_SESSION['user_id'];

    // Check if already applied
    $check = $pdo->prepare("SELECT id FROM applications WHERE task_id = ? AND user_id = ?");
    $check->execute([$task_id, $user_id]);
    if ($check->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO applications (task_id, user_id, proposal) VALUES (?,?,?)");
        $stmt->execute([$task_id, $user_id, $proposal]);
    }
    redirect("task.php?id=$task_id");
}
?>