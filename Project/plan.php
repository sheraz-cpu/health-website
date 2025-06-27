<?php include 'header.php'; ?>
<?php require_once 'db/db.php'; ?>

<section class="content">
  <h2>Your Personalized Diet Plan</h2>

  <?php
  if (!isset($_SESSION['user_id'])) {
      echo "<p style='color:red;'>You must be logged in to view your diet plan.</p>";
      include 'footer.php';
      exit;
  }

  $user_id = $_SESSION['user_id'];

  // Get the latest BMI record
  $stmt = $conn->prepare("SELECT bmi FROM bmi_records WHERE user_id = ? ORDER BY date DESC LIMIT 1");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $bmi = $row['bmi'];

      // Determine BMI status
      if ($bmi < 18.5) {
          $status = "Underweight";
          $diet = "
            <ul>
              <li>Eat 5–6 smaller meals during the day</li>
              <li>Choose nutrient-rich foods: whole grains, dairy, meats</li>
              <li>Add healthy snacks like nuts, cheese, and avocado</li>
              <li>Include strength training to build muscle mass</li>
            </ul>";
      } elseif ($bmi >= 18.5 && $bmi < 25) {
          $status = "Normal";
          $diet = "
            <ul>
              <li>Continue balanced meals: proteins, whole grains, veggies</li>
              <li>Stay hydrated with at least 2L of water daily</li>
              <li>Limit processed foods and added sugars</li>
              <li>Exercise 3–5 times a week for maintenance</li>
            </ul>";
      } else {
          $status = "Overweight";
          $diet = "
            <ul>
              <li>Focus on fiber-rich foods like fruits and vegetables</li>
              <li>Reduce intake of carbs and sugary snacks</li>
              <li>Try meal prep with lean proteins (chicken, fish)</li>
              <li>Engage in regular cardio: walking, cycling, etc.</li>
            </ul>";
      }

      echo "<p><strong>Your BMI:</strong> $bmi</p>";
      echo "<p><strong>Status:</strong> $status</p>";
      echo "<h3>Suggested Diet Plan:</h3>";
      echo $diet;

  } else {
      echo "<p>No BMI record found. Please calculate your BMI <a href='bmi.php'>here</a> first.</p>";
  }
  ?>
</section>

<?php include 'footer.php'; ?>
