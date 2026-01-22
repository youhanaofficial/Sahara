<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

requireRestaurant();

$user_id = $_SESSION['user_id'];
$sql = "SELECT id FROM restaurants WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$restaurant = mysqli_fetch_assoc($result);

$restaurant_id = $restaurant['id'];

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);

    $sql = "INSERT INTO food_posts (restaurant_id, description, quantity, status)
            VALUES (?, ?, ?, 'available')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $restaurant_id, $description, $quantity);
    mysqli_stmt_execute($stmt);

    $message = "Food added successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">

    <title>Add Surplus Food</title>
</head>
<body>

<h2>Add Surplus Food</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <textarea name="description" placeholder="Food description" required></textarea><br><br>
    <input type="text" name="quantity" placeholder="Quantity (e.g. 10 meals)" required><br><br>
    <button type="submit">Add Food</button>
</form>

<a href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>
