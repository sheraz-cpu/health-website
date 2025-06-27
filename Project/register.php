<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include 'header.php'; ?>
<?php require_once 'db/db.php'; ?>

<section class="content">
  <h2>📝 Register</h2>

  <?php
  $success = "";
  $error = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $fullname = trim($_POST["fullname"]);
      $email = trim($_POST["email"]);
      $password = trim($_POST["password"]);

      if ($fullname && $email && $password) {
          // Check if email already exists
          $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
          $checkStmt->bind_param("s", $email);
          $checkStmt->execute();
          $checkStmt->store_result();

          if ($checkStmt->num_rows > 0) {
              $error = "This email is already registered. Try logging in instead.";
          } else {
              $hashed_password = password_hash($password, PASSWORD_DEFAULT);

              $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
              $stmt->bind_param("sss", $fullname, $email, $hashed_password);

              if ($stmt->execute()) {
                  $success = "🎉 Registration successful! <a href='login.php'>Click here to log in</a>.";
              } else {
                  $error = "Registration failed. Please try again.";
              }
          }
      } else {
          $error = "All fields are required.";
      }
  }
  ?>

  <?php if ($success): ?>
    <p style="color: green; font-weight: bold;"><?= $success ?></p>
  <?php endif; ?>

  <?php if ($error): ?>
    <p style="color: red; font-weight: bold;"><?= $error ?></p>
  <?php endif; ?>

  <form method="post" action="">
    <label>Full Name:</label><br>
    <input type="text" name="fullname" required><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Register">
  </form>
</section>

<?php include 'footer.php'; ?>
