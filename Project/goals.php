<?php
include 'header.php';
require_once 'db/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;'>You must be logged in to view and manage your goals.</p>";
    include 'footer.php';
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $goal_title = $_POST['goal_title'];
    $target_value = $_POST['target_value'];
    $current_value = $_POST['current_value'];
    $unit = $_POST['unit'];
    $due_date = $_POST['due_date'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO user_goals (user_id, goal_title, target_value, current_value, unit, due_date, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $user_id, $goal_title, $target_value, $current_value, $unit, $due_date, $notes);
    $stmt->execute();
    $stmt->close();
}

// Fetch user goals
$user_id = $_SESSION['user_id'];
$result = $conn->prepare("SELECT * FROM user_goals WHERE user_id = ? ORDER BY due_date ASC");
$result->bind_param("i", $user_id);
$result->execute();
$goals = $result->get_result();
?>

<div class="content">
    <h2>🎯 Personal Health Goals</h2>

    <form method="POST" action="">
        <label>Goal Title:</label>
        <input type="text" name="goal_title" placeholder="e.g. Lose Weight" required>

        <label>Target Value:</label>
        <input type="text" name="target_value" placeholder="e.g. 60" required>

        <label>Current Value:</label>
        <input type="text" name="current_value" placeholder="e.g. 70" required>

        <label>Unit:</label>
        <input type="text" name="unit" placeholder="e.g. kg, steps, bpm" required>

        <label>Due Date:</label>
        <input type="date" name="due_date" required>

        <label>Notes:</label>
        <textarea name="notes" placeholder="Optional notes..."></textarea>

        <button type="submit">Add Goal</button>
    </form>

    <hr>

    <h3>📅 Active Goals</h3>

    <?php if ($goals->num_rows > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top:10px;">
            <tr>
                <th>Title</th>
                <th>Progress</th>
                <th>Due Date</th>
                <th>Notes</th>
            </tr>
            <?php while ($goal = $goals->fetch_assoc()): 
                $target = floatval($goal['target_value']);
                $current = floatval($goal['current_value']);
                $progress = $target > 0 ? min(100, round(($current / $target) * 100)) : 0;
            ?>
                <tr>
                    <td><?= htmlspecialchars($goal['goal_title']) ?></td>
                    <td>
                        <?= htmlspecialchars($goal['current_value']) ?> / <?= htmlspecialchars($goal['target_value']) ?> <?= htmlspecialchars($goal['unit']) ?><br>
                        <div style="background:#eee; border-radius:5px; width:100%;">
                            <div style="background:#28a745; width:<?= $progress ?>%; color:white; text-align:center; padding:2px 0; border-radius:5px;">
                                <?= $progress ?>%
                            </div>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($goal['due_date']) ?></td>
                    <td><?= htmlspecialchars($goal['notes']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No goals added yet. Set a goal to get started!</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
