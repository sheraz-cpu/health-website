<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Health, Nutrition & Diet Guide</title>
  <link rel="stylesheet" href="style.css">
  <style>
    nav ul {
      list-style-type: none;
      display: flex;
      flex-wrap: wrap;
      background-color: #28a745;
      padding: 10px;
      margin: 0;
    }

    nav ul li {
      margin: 0 10px;
    }

    nav ul li a,
    nav ul li form input[type="submit"] {
      text-decoration: none;
      color: white;
      background: transparent;
      border: none;
      cursor: pointer;
      font-weight: bold;
      padding: 8px 12px;
      transition: background 0.3s;
    }

    nav ul li a:hover,
    nav ul li form input[type="submit"]:hover {
      background-color: #1e7e34;
      border-radius: 5px;
    }

    .user-info {
      margin-left: auto;
      color: #fff;
      font-weight: bold;
      padding: 8px 12px;
    }

    .logout-btn {
      color: white;
      background: none;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <header>
  <div style="text-align: center; padding: 10px 0;">
    <a href="index.php">
      <img src="assets/logo.png" alt="Site Logo" style="max-height: 85px;">
    </a>
  </div>
  <nav>
    <ul>


        <li><a href="index.php">🏠 Home</a></li>
        <li><a href="bmi.php">🧮 BMI</a></li>
        <li><a href="plan.php">🥗 Plan</a></li>
        <li><a href="contact.php">📩 Contact</a></li>

        <?php if (isset($_SESSION["user_id"])): ?>
          <li><a href="food_diary.php">📘 Food Diary</a></li>
          <li><a href="exercise.php">🏃 Exercise</a></li>
          <li><a href="metrics.php">💉 Metrics</a></li>
          <li><a href="goals.php">🎯 Goals</a></li>
          <li>
            <form action="logout.php" method="post" style="display:inline;">
              <input type="submit" value="🚪 Logout" class="logout-btn">
            </form>
          </li>
          <li class="user-info">👤 <?= htmlspecialchars($_SESSION["user_name"]) ?></li>
        <?php else: ?>
          <li><a href="login.php">🔐 Login</a></li>
          <li><a href="register.php">📝 Register</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>
  <main>
