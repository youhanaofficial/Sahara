<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

requireRestaurant();

// Get restaurant id using logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT id FROM restaurants WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$restaurant = mysqli_fetch_assoc($result);

$restaurant_id = $restaurant['id'];
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">

    <title>Restaurant Dashboard | Sahara</title>
</head>
<body>

<h2>Restaurant Dashboard</h2>

<button style="position: absolute; top: 20px; right: 20px; padding: 0; overflow: hidden; width: 30vw;">
    <a href="../logout.php" style="display: block; padding: 10px 20px; text-decoration: none; color: black;">
        Logout
    </a>
</button>

<a href="add_food.php">➕ Add Surplus Food</a>

<h3>Your Food Posts</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Description</th>
    <th>Quantity</th>
    <th>Status</th>
</tr>

<?php
$sql = "SELECT description, quantity, status FROM food_posts WHERE restaurant_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $restaurant_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    echo "<tr><td colspan='3'>No food posted yet</td></tr>";
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['description']}</td>";
    echo "<td>{$row['quantity']}</td>";
    echo "<td>{$row['status']}</td>";
    echo "</tr>";
}
?>

</table>

<hr>

<h3>Charity Urgency Status</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Charity Name</th>
    <th>City</th>
    <th>State</th>
    <th>Urgency</th>
</tr>

<?php
$sql = "
SELECT c.charity_name, c.city, c.state, cs.urgency
FROM charities c
LEFT JOIN charity_status cs ON c.id = cs.charity_id
";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo '<tr><td colspan="4">No charity data available</td></tr>';
}

while ($row = mysqli_fetch_assoc($result)) {
    $urgency = $row['urgency'] ?? 'normal';
    echo "<tr>
        <td>{$row['charity_name']}</td>
        <td>{$row['city']}</td>
        <td>{$row['state']}</td>
        <td>{$urgency}</td>
    </tr>";
}
?>
</table>

<hr>

<h3>Active Foodraisers</h3>

<table border="1" cellpadding="8">
<tr>
    <th>Charity</th>
    <th>Food Needed</th>
    <th>Description</th>
    <th>Urgency</th>
</tr>

<?php
$sql = "
SELECT f.title, f.description, f.urgency, c.charity_name
FROM foodraisers f
JOIN charities c ON f.charity_id = c.id
";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo '<tr><td colspan="4">No foodraisers available</td></tr>';
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
        <td>{$row['charity_name']}</td>
        <td>{$row['title']}</td>
        <td>{$row['description']}</td>
        <td>{$row['urgency']}</td>
    </tr>";
}
?>
</table>

</body>
</html>
