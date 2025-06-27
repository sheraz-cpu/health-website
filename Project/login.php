<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include 'header.php'; ?>
<?php require_once 'db/db.php'; ?>

<section class="content">
  <h2>🔐 Login</h2>

  <form method="post" action="">
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
      $stmt->bind_result($id, $fullname, $hashed_password);
      $stmt->fetch();

      // If using password_hash() to store password
      if (password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["user_name"] = $fullname; // ← consistent session key
        header("Location: index.php");
        exit;
      } else {
        echo "<p style='color:red;'>Invalid email or password.</p>";
      }
    } else {
      echo "<p style='color:red;'>Invalid email or password.</p>";
    }
  }
  ?>
</section>

<?php include 'footer.php'; ?>
