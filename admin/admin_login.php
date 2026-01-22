<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../includes/db.php";
require_once "../includes/auth.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($password, $admin['password'])) {

        if ($admin['status'] !== 'approved') {
            $error = "Admin access not approved.";
        } else {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['role'] = 'admin';

            header("Location: dashboard.php");
            exit();
        }

    } else {
        $error = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">

    <title>Admin Login | Sahara</title>
</head>
<body>

<h2>Admin Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="email" name="email" placeholder="Admin Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>
