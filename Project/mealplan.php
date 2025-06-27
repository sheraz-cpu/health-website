<?php include 'header.php'; ?>
<section class="content">
  <h2>🥗 Generate Your Meal Plan</h2>

  <form method="POST" action="">
    <label for="diet_type">Select Diet Type:</label>
    <select name="diet_type" id="diet_type" required>
      <option value="">-- Choose a diet --</option>
      <option value="Anything">Anything</option>
      <option value="Keto">Keto</option>
      <option value="Mediterranean">Mediterranean</option>
      <option value="Paleo">Paleo</option>
      <option value="Vegan">Vegan</option>
      <option value="Vegetarian">Vegetarian</option>
    </select>

    <label for="calories">Daily Calorie Goal (kcal):</label>
    <input type="number" name="calories" id="calories" required min="1000" step="50">

    <label for="meals">Number of Meals:</label>
    <input type="number" name="meals" id="meals" required min="1" max="6">

    <button type="submit">Generate Plan</button>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $diet = $_POST['diet_type'];
      $calories = (int)$_POST['calories'];
      $meals = (int)$_POST['meals'];

      // Macronutrient splits by diet
      $macros = [
          'Anything' => ['carbs' => 50, 'protein' => 20, 'fat' => 30],
          'Keto' => ['carbs' => 5, 'protein' => 25, 'fat' => 70],
          'Mediterranean' => ['carbs' => 50, 'protein' => 20, 'fat' => 30],
          'Paleo' => ['carbs' => 35, 'protein' => 30, 'fat' => 35],
          'Vegan' => ['carbs' => 60, 'protein' => 20, 'fat' => 20],
          'Vegetarian' => ['carbs' => 55, 'protein' => 20, 'fat' => 25],
      ];

      $split = $macros[$diet];

      // Calculate grams
      $carbs_g = round(($split['carbs'] / 100) * $calories / 4);
      $protein_g = round(($split['protein'] / 100) * $calories / 4);
      $fat_g = round(($split['fat'] / 100) * $calories / 9);
  ?>
    <section class="content">
      <h2>🍽️ Sample Meals for <?= htmlspecialchars($diet) ?> Diet</h2>
      <div class="features">
        <?php
        // Sample meals array
        $sample_meals = [
          'Keto' => [
            ['name' => 'Avocado Egg Salad', 'desc' => 'Healthy fats from avocado and protein-rich eggs.', 'img' => 'images/keto1.jpg'],
            ['name' => 'Grilled Chicken & Zucchini', 'desc' => 'Low-carb grilled chicken served with sautéed zucchini.', 'img' => 'images/keto2.jpg'],
          ],
          'Vegan' => [
            ['name' => 'Quinoa Buddha Bowl', 'desc' => 'A bowl of quinoa, chickpeas, and fresh veggies.', 'img' => 'images/vegan1.jpg'],
            ['name' => 'Tofu Stir-Fry', 'desc' => 'Tofu with colorful stir-fried vegetables and brown rice.', 'img' => 'images/vegan2.jpg'],
          ],
          'Mediterranean' => [
            ['name' => 'Greek Salad & Hummus', 'desc' => 'Fresh veggies with olives, feta, and hummus.', 'img' => 'images/med1.jpg'],
            ['name' => 'Grilled Salmon with Couscous', 'desc' => 'Omega-3-rich salmon with herbed couscous.', 'img' => 'images/med2.jpg'],
          ],
          'Paleo' => [
            ['name' => 'Beef Lettuce Wraps', 'desc' => 'Grass-fed beef in crisp lettuce leaves.', 'img' => 'images/paleo1.jpg'],
            ['name' => 'Sweet Potato Hash', 'desc' => 'Eggs and sweet potatoes cooked paleo-style.', 'img' => 'images/paleo2.jpg'],
          ],
          'Vegetarian' => [
            ['name' => 'Veggie Omelette', 'desc' => 'Egg omelette packed with bell peppers and spinach.', 'img' => 'images/veg1.jpg'],
            ['name' => 'Mushroom Risotto', 'desc' => 'Creamy rice dish with mushrooms and peas.', 'img' => 'images/veg2.jpg'],
          ],
          'Anything' => [
            ['name' => 'Chicken Rice Bowl', 'desc' => 'Balanced meal with chicken, rice, and veggies.', 'img' => 'images/any1.jpg'],
            ['name' => 'Pasta with Marinara', 'desc' => 'Whole wheat pasta with tomato sauce and cheese.', 'img' => 'images/any2.jpg'],
          ]
        ];

        foreach ($sample_meals[$diet] as $meal) {
          echo '
          <div class="feature-box" style="width: 280px; text-align: center;">
            <img src="' . $meal['img'] . '" alt="' . $meal['name'] . '" style="width:100%; height:170px; object-fit:cover; border-radius: 8px;">
            <h3>' . $meal['name'] . '</h3>
            <p>' . $meal['desc'] . '</p>
          </div>';
        }
        ?>
      </div>
    </section>
  

    <section class="dashboard">
      <h2>📊 Macronutrient Breakdown</h2>
      <p><strong>Diet Type:</strong> <?= htmlspecialchars($diet) ?></p>
      <p><strong>Daily Calories:</strong> <?= $calories ?> kcal</p>
      <p><strong>Meals per Day:</strong> <?= $meals ?></p>

      <div class="dashboard-cards">
        <div class="card">🍞 Carbohydrates: <strong><?= $carbs_g ?>g</strong> (<?= $split['carbs'] ?>%)</div>
        <div class="card">🍗 Protein: <strong><?= $protein_g ?>g</strong> (<?= $split['protein'] ?>%)</div>
        <div class="card">🥑 Fat: <strong><?= $fat_g ?>g</strong> (<?= $split['fat'] ?>%)</div>
      </div>
    </section>

  <?php } ?>
</section>
<?php include 'footer.php'; ?>
