<?php
include 'header.php';
require_once 'db/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to view your exercise log.</p>";
    include 'footer.php';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $exercise_type = $_POST['exercise_type'];
    $duration_minutes = $_POST['duration_minutes'];
    $calories_burned = $_POST['calories_burned'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO exercise_log (user_id, date, exercise_type, duration_minutes, calories_burned, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issiis", $user_id, $date, $exercise_type, $duration_minutes, $calories_burned, $notes);
    $stmt->execute();
    $stmt->close();
}

// Fetch exercise entries
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM exercise_log WHERE user_id = ? ORDER BY date DESC");
$result->bind_param("i", $user_id);
$result->execute();
$entries = $result->get_result();
?>

<div class="content">
    <h2>🏃 Exercise Log</h2>

    <form method="POST" action="">
        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Exercise Type:</label>
        <input type="text" name="exercise_type" placeholder="e.g. Running" required>

        <label>Duration (minutes):</label>
        <input type="number" name="duration_minutes" placeholder="e.g. 30" required>

        <label>Calories Burned:</label>
        <input type="number" name="calories_burned" placeholder="e.g. 250" required>

        <label>Notes:</label>
        <textarea name="notes" placeholder="Optional notes..."></textarea>

        <button type="submit">Add Entry</button>
    </form>

    <hr>

    <h3>📅 Past Exercises</h3>

    <?php if ($entries->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:10px;">
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Duration (min)</th>
                <th>Calories</th>
                <th>Notes</th>
            </tr>
            <?php while ($row = $entries->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['exercise_type']) ?></td>
                    <td><?= htmlspecialchars($row['duration_minutes']) ?></td>
                    <td><?= htmlspecialchars($row['calories_burned']) ?></td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No exercise entries found yet. Start logging your workouts!</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
