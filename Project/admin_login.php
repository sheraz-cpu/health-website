<?php
session_start();

$admin_user = "admin";
$admin_pass = "admin123"; // 🔐 Change to a strong password in real use

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION["admin_logged_in"] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
  <h2>Admin Login</h2>
  <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>

  <form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
  </form>
</div>
</body>
</html>
