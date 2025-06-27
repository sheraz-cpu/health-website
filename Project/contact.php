<?php include 'header.php'; ?>
<?php require_once 'db/db.php'; ?>

<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if ($name && $email && $message) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success = "Thanks, $name. We received your message!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<section class="content">
    <h2>📩 Contact / Feedback</h2>

    <?php if ($success): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" class="contact-form">
        <label for="name">Your Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Your Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="message">Your Message:</label>
        <textarea name="message" id="message" rows="5" required></textarea>

        <button type="submit">Send Message</button>
    </form>
</section>

<?php include 'footer.php'; ?>
