<?php
include 'header.php';
require_once 'db/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to view health metrics.</p>";
    include 'footer.php';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $metric_type = $_POST['metric_type'];
    $value = $_POST['value'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO health_metrics (user_id, date, metric_type, value, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $date, $metric_type, $value, $notes);
    $stmt->execute();
    $stmt->close();
}

// Fetch user metrics
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM health_metrics WHERE user_id = ? ORDER BY date DESC");
$result->bind_param("i", $user_id);
$result->execute();
$entries = $result->get_result();
?>

<div class="content">
    <h2>💉 Health Metrics</h2>

    <form method="POST" action="">
        <label>Date:</label>
        <input type="date" name="date" required>

        <label>Metric Type:</label>
        <input type="text" name="metric_type" placeholder="e.g. Blood Pressure, Heart Rate" required>

        <label>Value:</label>
        <input type="text" name="value" placeholder="e.g. 120/80 mmHg or 75 bpm" required>

        <label>Notes:</label>
        <textarea name="notes" placeholder="Optional notes..."></textarea>

        <button type="submit">Add Metric</button>
    </form>

    <hr>

    <h3>📊 Tracked Metrics</h3>

    <?php if ($entries->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:10px;">
            <tr>
                <th>Date</th>
                <th>Metric Type</th>
                <th>Value</th>
                <th>Notes</th>
            </tr>
            <?php while ($row = $entries->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['metric_type']) ?></td>
                    <td><?= htmlspecialchars($row['value']) ?></td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No health metrics recorded yet. Start tracking your vital signs today!</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
