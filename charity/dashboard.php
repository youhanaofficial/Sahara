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

// Handle urgency update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['urgency'])) {
    $urgency = $_POST['urgency'];

    $sql = "INSERT INTO charity_status (charity_id, urgency)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE urgency = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iss", $charity_id, $urgency, $urgency);
    mysqli_stmt_execute($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">

    <title>Charity Dashboard | Sahara</title>
</head>
<body>

<h2>Charity Dashboard</h2>

<button style="position: absolute; top: 20px; right: 20px; padding: 0; overflow: hidden; width: 30vw;">
    <a href="../logout.php" style="display: block; padding: 10px 20px; text-decoration: none; color: black;">
        Logout
    </a>
</button> 

<h3>Set Food Urgency</h3>
<form method="POST">
    <select name="urgency" required>
        <option value="normal">Normal</option>
        <option value="low">Low</option>
        <option value="urgent">Urgent</option>
    </select>
    <button type="submit">Update</button>
</form>

<br>

<a href="foodraiser.php">➕ Create Foodraiser</a>

<!-- restaurant post section -->
<hr>

<h3>Available Food from Restaurants</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Restaurant</th>
    <th>Phone</th>
    <th>City</th>
    <th>State</th>
    <th>Food Description</th>
    <th>Quantity</th>
    <th>Status</th>
</tr>

<?php
$sql = "
SELECT r.restaurant_name, r.phone, r.city, r.state,
       f.description, f.quantity, f.status
FROM food_posts f
JOIN restaurants r ON f.restaurant_id = r.id
WHERE f.status = 'available'
";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo '<tr><td colspan="6">No food available at the moment</td></tr>';
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['restaurant_name']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['city']}</td>
        <td>{$row['state']}</td>
        <td>{$row['description']}</td>
        <td>{$row['quantity']}</td>
        <td>{$row['status']}</td>
    </tr>";
}
?>
</table>


</body>
</html>
