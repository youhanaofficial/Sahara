<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

requireAdmin();
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">

    <title>Admin Dashboard | Sahara</title>
</head>
<body>

<h2>Admin Dashboard</h2>

<button style="position: absolute; top: 20px; right: 20px; padding: 0; overflow: hidden; width: 30vw;">
    <a href="../logout.php" style="display: block; padding: 10px 20px; text-decoration: none; color: black;">
        Logout
    </a>
</button>

<h3>Pending User Approvals</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

<?php
$sql = "SELECT id, email, role, status FROM users WHERE status = 'pending'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "<tr><td colspan='5'>No pending users</td></tr>";
}

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['email']}</td>";
    echo "<td>{$row['role']}</td>";
    echo "<td>{$row['status']}</td>";
    echo "<td>
        <a href='approve_users.php?id={$row['id']}&action=approve'>Approve</a> |
        <a href='approve_users.php?id={$row['id']}&action=reject'>Reject</a>
    </td>";
    echo "</tr>";
}
?>

</table>

</body>
</html>
