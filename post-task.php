<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $budget = $_POST['budget'];
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user_id'];

    // Handle file attachment
    $attachment = null;
    if ($_FILES['attachment']['error'] == 0) {
        $ext = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = 'task_' . time() . '_' . rand(1000,9999) . '.' . $ext;
        move_uploaded_file($_FILES['attachment']['tmp_name'], 'assets/uploads/' . $filename);
        $attachment = $filename;
    }

    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, category, budget, deadline, attachment) VALUES (?,?,?,?,?,?,?)");
    if ($stmt->execute([$user_id, $title, $description, $category, $budget, $deadline, $attachment])) {
        redirect('my-tasks.php');
    } else {
        $error = "Failed to post task.";
    }
}
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Post a New Task</div>
            <div class="card-body">
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="5" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option>Presentation</option>
                                <option>Synopsis</option>
                                <option>Thesis</option>
                                <option>Survey</option>
                                <option>Data Analysis</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Budget (Rs.)</label>
                            <input type="number" name="budget" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Deadline</label>
                            <input type="date" name="deadline" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Attachment (optional)</label>
                        <input type="file" name="attachment" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Post Task (Free)</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>