<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$name = $_SESSION["fullname"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>
<p><a href="index.php">🏠 Back to Home</a></p>

<div class="content">
    <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>
    <p>Select an option below:</p>
    <ul>
        <li><a href="bmi.html">🧮 BMI Calculator</a></li>
        <li><a href="plan.php">🥗 Personalized Diet Plan</a></li>
        <li><a href="contact.php">📩 Send Feedback</a></li>
        <li><a href="logout.php">🚪 Logout</a></li>
    </ul>
</div>
</body>
</html>
