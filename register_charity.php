<?php
require_once "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $charity_name = trim($_POST['charity_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $verification = trim($_POST['verification']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);

    // Check if email already exists
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
    mysqli_stmt_bind_param($check, "s", $email);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        $message = "Email already registered.";
    } else {

        // Insert into users table
        $sql = "INSERT INTO users (email, password, role, status) VALUES (?, ?, 'charity', 'pending')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);

        $user_id = mysqli_insert_id($conn);

        // Insert into charities table
        $sql2 = "INSERT INTO charities (user_id, charity_name, phone, verification_number, city, state)
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "isssss",
            $user_id,
            $charity_name,
            $phone,
            $verification,
            $city,
            $state
        );
        mysqli_stmt_execute($stmt2);

        $message = "Registration successful. Await admin approval.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once __DIR__ . "/includes/config.php"; ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/images/favicon.ico">
    <title>Charity Registration | Sahara</title>
</head>
<body>

<h2>Charity Registration</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="charity_name" placeholder="Charity Name" required><br><br>
    <input type="text" name="phone" placeholder="Phone Number" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="text" name="verification" placeholder="Verification Number" required><br><br>
    <input type="text" name="city" placeholder="City" required><br><br>
    <input type="text" name="state" placeholder="State" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
