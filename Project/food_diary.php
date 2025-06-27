<?php
include 'header.php';
require_once 'db/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to access the food diary.</p>";
    include 'footer.php';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $meal_type = $_POST['meal_type'];
    $food_item = $_POST['food_item'];
    $calories = $_POST['calories'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO food_diary (user_id, date, meal_type, food_item, calories, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssis", $user_id, $date, $meal_type, $food_item, $calories, $notes);
    $stmt->execute();
    $stmt->close();
}

// Fetch diary entries
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM food_diary WHERE user_id = ? ORDER BY date DESC");
$result->bind_param("i", $user_id);
$result->execute();
$entries = $result->get_result();
?>

<div class="content">
    <h2>🥗 Food Diary</h2>

    <form method="POST" action="">
        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Meal Type:</label>
        <input type="text" name="meal_type" placeholder="e.g. Breakfast" required>

        <label>Food Item:</label>
        <input type="text" name="food_item" placeholder="e.g. Oatmeal" required>

        <label>Calories:</label>
        <input type="number" name="calories" placeholder="e.g. 250" required>

        <label>Notes:</label>
        <textarea name="notes" placeholder="Optional notes..."></textarea>

        <button type="submit">Add Entry</button>
    </form>

    <hr>

    <h3>📅 Past Entries</h3>

    <?php if ($entries->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:10px;">
            <tr>
                <th>Date</th>
                <th>Meal</th>
                <th>Food</th>
                <th>Calories</th>
                <th>Notes</th>
            </tr>
            <?php while ($row = $entries->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['meal_type']) ?></td>
                    <td><?= htmlspecialchars($row['food_item']) ?></td>
                    <td><?= htmlspecialchars($row['calories']) ?></td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No entries found. Start logging your meals today!</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
