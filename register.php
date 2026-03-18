<?php require_once 'includes/config.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $university = trim($_POST['university']);
    $course = trim($_POST['course']);
    $year = trim($_POST['year']);

    // Check if email exists
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, university, course, year) VALUES (?,?,?,?,?,?)");
        if ($stmt->execute([$name, $email, $password, $university, $course, $year])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            redirect('profile.php');
        } else {
            $error = "Registration failed.";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Register</div>
            <div class="card-body">
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="post">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>University</label>
                        <input type="text" name="university" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Course/Degree</label>
                        <input type="text" name="course" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Year</label>
                        <input type="text" name="year" class="form-control" placeholder="e.g., 2nd year">
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>