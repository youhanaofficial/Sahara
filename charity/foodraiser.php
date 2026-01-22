<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

requireCharity();

$user_id = $_SESSION['user_id'];

// Get charity ID
$sql = "SELECT id FROM charities WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$charity = mysqli_fetch_assoc($result);

$charity_id = $charity['id'];

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $urgency = $_POST['urgency'];

    $sql = "INSERT INTO foodraisers (charity_id, title, description, urgency)
            VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $charity_id, $title, $description, $urgency);
    mysqli_stmt_execute($stmt);

    $message = "Foodraiser created successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">

    <title>Create Foodraiser</title>
</head>
<body>

<h2>Create Foodraiser</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="title" placeholder="Food needed (e.g. Rice, Meals)" required><br><br>
    <textarea name="description" placeholder="Details" required></textarea><br><br>

    <select name="urgency" required>
        <option value="normal">Normal</option>
        <option value="low">Low</option>
        <option value="urgent">Urgent</option>
    </select><br><br>

    <button type="submit">Create</button>
</form>

<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>
