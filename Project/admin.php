<?php
session_start();
if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'db/db.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO tips (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
    }
}

// Handle deletion
if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    $conn->query("DELETE FROM tips WHERE id = $id");
}

// Fetch all tips
$result = $conn->query("SELECT * FROM tips ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Manage Tips</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="content">
    <h2>Admin Panel - Nutrition Tips</h2>

    <form method="POST">
        <label>Tip Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Tip Content:</label><br>
        <textarea name="content" rows="5" required></textarea><br><br>

        <input type="submit" value="Add Tip">
    </form>

    <hr>

    <h3>Existing Tips</h3>
    <table border="1" cellpadding="10">
        <tr><th>ID</th><th>Title</th><th>Content</th><th>Action</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row["id"] ?></td>
                <td><?= htmlspecialchars($row["title"]) ?></td>
                <td><?= htmlspecialchars($row["content"]) ?></td>
                <td><a href="?delete=<?= $row["id"] ?>" onclick="return confirm('Delete this tip?')">Delete</a></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
