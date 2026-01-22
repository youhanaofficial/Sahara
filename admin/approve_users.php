<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

requireAdmin();

if (!isset($_GET['id'], $_GET['action'])) {
    die("Invalid request");
}

$user_id = intval($_GET['id']);
$action = $_GET['action'];

if ($action === 'approve') {
    $status = 'approved';
} elseif ($action === 'reject') {
    $status = 'rejected';
} else {
    die("Invalid action");
}

$sql = "UPDATE users SET status = ? WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "si", $status, $user_id);
mysqli_stmt_execute($stmt);

header("Location: dashboard.php");
exit();
