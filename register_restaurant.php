<?php
require_once "includes/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $restaurant_name = trim($_POST['restaurant_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $fssai = trim($_POST['fssai']);
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
        $sql = "INSERT INTO users (email, password, role, status) VALUES (?, ?, 'restaurant', 'pending')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);

        $user_id = mysqli_insert_id($conn);

        // Insert into restaurants table
        $sql2 = "INSERT INTO restaurants (user_id, restaurant_name, phone, fssai_number, city, state)
                 VALUES (?, ?, ?, ?, ?, ?)";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "isssss",
            $user_id,
            $restaurant_name,
            $phone,
            $fssai,
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
    <title>Restaurant Registration | Sahara</title>
</head>
<body>

<h2>Restaurant Registration</h2>

<?php if ($message): ?>
    <p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="restaurant_name" placeholder="Restaurant Name" required><br><br>
    <input type="text" name="phone" placeholder="Phone Number" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="text" name="fssai" placeholder="FSSAI Number" required><br><br>
    <input type="text" name="city" placeholder="City" required><br><br>
    <input type="text" name="state" placeholder="State" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
