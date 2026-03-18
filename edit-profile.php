<?php require_once 'includes/config.php';
if (!isLoggedIn()) redirect('login.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $university = $_POST['university'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $bio = $_POST['bio'];
    $skills = $_POST['skills'];

    // Handle avatar upload
    if ($_FILES['avatar']['error'] == 0) {
        $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['avatar']['tmp_name'], 'assets/uploads/avatars/' . $filename);
        // Update avatar in DB
        $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?")->execute([$filename, $user_id]);
    }

    $stmt = $pdo->prepare("UPDATE users SET name=?, university=?, course=?, year=?, bio=?, skills=? WHERE id=?");
    $stmt->execute([$name, $university, $course, $year, $bio, $skills, $user_id]);
    redirect('profile.php');
}

$user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$user->execute([$user_id]);
$user = $user->fetch();
?>
<?php include 'includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Profile</div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?= escape($user['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>University</label>
                        <input type="text" name="university" class="form-control" value="<?= escape($user['university']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Course/Degree</label>
                        <input type="text" name="course" class="form-control" value="<?= escape($user['course']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Year</label>
                        <input type="text" name="year" class="form-control" value="<?= escape($user['year']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Bio</label>
                        <textarea name="bio" class="form-control" rows="3"><?= escape($user['bio']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Skills (comma separated)</label>
                        <input type="text" name="skills" class="form-control" value="<?= escape($user['skills']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Avatar</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>