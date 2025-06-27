<?php include 'header.php'; ?>
<?php require_once 'db/db.php'; ?>

<section class="content">
  <h2>🧮 BMI Calculator</h2>

  <?php
  if (!isset($_SESSION['user_id'])) {
      echo "<p style='color:red;'>You must be logged in to calculate and save your BMI.</p>";
      include 'footer.php';
      exit;
  }

  $bmi = '';
  $status = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $height = floatval($_POST['height']);
      $weight = floatval($_POST['weight']);

      if ($height > 0 && $weight > 0) {
          $bmi = round($weight / (($height / 100) ** 2), 2);

          if ($bmi < 18.5) {
              $status = "Underweight";
          } elseif ($bmi < 25) {
              $status = "Normal weight";
          } elseif ($bmi < 30) {
              $status = "Overweight";
          } else {
              $status = "Obese";
          }

          // Save BMI record in database
          $user_id = $_SESSION['user_id'];
          $stmt = $conn->prepare("INSERT INTO bmi_records (user_id, height, weight, bmi, date) VALUES (?, ?, ?, ?, CURDATE())");
          $stmt->bind_param("iidd", $user_id, $height, $weight, $bmi);
          $stmt->execute();
      } else {
          echo "<p style='color:red;'>Please enter valid height and weight values.</p>";
      }
  }
  ?>

  <form method="post" action="">
    <label for="height">Height (cm):</label>
    <input type="number" name="height" id="height" required step="0.1">

    <label for="weight">Weight (kg):</label>
    <input type="number" name="weight" id="weight" required step="0.1">

    <button type="submit">Calculate BMI</button>
  </form>

  <?php if ($bmi): ?>
    <div class="bmi-result">
      <h3>Your BMI: <?= $bmi ?></h3>
      <p>Status: <strong><?= $status ?></strong></p>
      <p>Your result has been saved to your profile.</p>
      <a href="plan.php">View Your Personalized Diet Plan →</a>
    </div>
  <?php endif; ?>
</section>

<img src="assets/BMI_chart.png" alt="BMI Chart" style="max-width: 100%; margin: 30px auto; display: block;">

<?php include 'footer.php'; ?>
