<?php include 'header.php'; ?>
<?php require_once 'db/db.php'; ?>

<section class="content">
  <h2>Welcome to the Health, Nutrition & Diet Guide</h2>
  <p>This platform helps you monitor your health, calculate your BMI, and access diet plans and practical nutrition tips.</p>
</section>

<?php
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$tipsPerPage = 2;
$offset = ($page - 1) * $tipsPerPage;

$tips = $conn->query("SELECT title, content FROM tips ORDER BY id DESC LIMIT $offset, $tipsPerPage");
$totalTips = $conn->query("SELECT COUNT(*) as total FROM tips")->fetch_assoc()['total'];
$totalPages = ceil($totalTips / $tipsPerPage);
?>

<section class="content">
  <h2>Latest Nutrition Tips</h2>

  <?php if ($tips->num_rows > 0): ?>
    <?php while ($row = $tips->fetch_assoc()): ?>
      <div class="tips-box">
        <h4><?= htmlspecialchars($row["title"]) ?></h4>
        <p><?= nl2br(htmlspecialchars($row["content"])) ?></p>
      </div>
    <?php endwhile; ?>

    <div style="text-align:center;">
      <?php if ($page > 1): ?>
        <a href="index.php?page=<?= $page - 1 ?>">⏮ Prev</a>
      <?php endif; ?>
      <?php if ($page < $totalPages): ?>
        <a href="index.php?page=<?= $page + 1 ?>">Next ⏭</a>
      <?php endif; ?>
    </div>
  <?php else: ?>
    <p>No nutrition tips found.</p>
  <?php endif; ?>
</section>

<?php
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Get today's calories from food_diary
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT SUM(calories) as total_calories FROM food_diary WHERE user_id = ? AND date = ?");
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $total_calories = $result['total_calories'] ?? 0;

    // Get today's calories burned from exercise_log
    $stmt = $conn->prepare("SELECT SUM(calories_burned) as total_burned FROM exercise_log WHERE user_id = ? AND date = ?");
    $stmt->bind_param("is", $user_id, $today);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $total_burned = $result['total_burned'] ?? 0;

    // Get total goals
    $goals_result = $conn->query("SELECT COUNT(*) as total_goals FROM user_goals WHERE user_id = $user_id");
    $total_goals = $goals_result->fetch_assoc()['total_goals'];
?>
<section class="content dashboard">
  <h2>📊 Your Health Summary</h2>
  <div class="dashboard-cards">
    <div class="card">
      🍽️ Calories Eaten Today: <strong><?= $total_calories ?></strong> kcal
      <br><a class="view-link" href="food_diary.php">View Diary</a>
    </div>
    <div class="card">
      🔥 Calories Burned Today: <strong><?= $total_burned ?></strong> kcal
      <br><a class="view-link" href="exercise.php">View Exercise</a>
    </div>
    <div class="card">
      🎯 Active Goals: <strong><?= $total_goals ?></strong>
      <br><a class="view-link" href="goals.php">View Goals</a>
    </div>
  </div>
</section>
<?php } ?>




<?php include 'footer.php'; ?>
