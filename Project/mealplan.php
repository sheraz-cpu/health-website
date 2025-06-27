<?php include 'header.php'; ?>

<!-- Add React CDN before your content -->
<script crossorigin src="https://unpkg.com/react@18/umd/react.development.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<style>
/* Enhanced styles for React components */
.react-form-container {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 2rem;
  border-radius: 15px;
  margin: 2rem 0;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.react-form {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
  font-weight: 600;
}

.form-control {
  width: 100%;
  padding: 12px;
  border: 2px solid #e1e5e9;
  border-radius: 8px;
  font-size: 16px;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #667eea;
  outline: none;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-generate {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 15px 30px;
  border-radius: 8px;
  font-size: 18px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
}

.btn-generate:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-generate:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.loading-spinner {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 3px solid rgba(255,255,255,.3);
  border-radius: 50%;
  border-top-color: #fff;
  animation: spin 1s ease-in-out infinite;
  margin-right: 10px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.macro-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin: 2rem 0;
}

.macro-card {
  background: white;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  text-align: center;
  transition: transform 0.3s ease;
}

.macro-card:hover {
  transform: translateY(-5px);
}

.macro-progress {
  width: 100%;
  height: 8px;
  background: #e1e5e9;
  border-radius: 4px;
  margin: 10px 0;
  overflow: hidden;
}

.macro-progress-bar {
  height: 100%;
  border-radius: 4px;
  transition: width 0.5s ease;
}

.carbs { background: linear-gradient(135deg, #ffeaa7, #fdcb6e); }
.protein { background: linear-gradient(135deg, #fd79a8, #e84393); }
.fat { background: linear-gradient(135deg, #a29bfe, #6c5ce7); }

.meal-suggestions {
  margin-top: 2rem;
}

.suggestions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1.5rem;
  margin: 1rem 0;
}

.meal-card {
  background: white;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
}

.meal-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 40px rgba(0,0,0,0.2);
}

.meal-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.meal-content {
  padding: 1.5rem;
}

.meal-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 0.5rem;
}

.meal-description {
  color: #666;
  line-height: 1.5;
}
</style>

<section class="content">
  <h2>🥗 Generate Your Meal Plan</h2>
  
  <!-- React Form Container -->
  <div id="meal-plan-form"></div>
  
  <!-- PHP Results Section -->
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

      // Pass data to JavaScript for React components
      echo "<script>
        window.mealPlanData = {
          diet: '" . htmlspecialchars($diet) . "',
          calories: $calories,
          meals: $meals,
          macros: {
            carbs: { grams: $carbs_g, percentage: {$split['carbs']} },
            protein: { grams: $protein_g, percentage: {$split['protein']} },
            fat: { grams: $fat_g, percentage: {$split['fat']} }
          },
          sampleMeals: " . json_encode($sample_meals[$diet]) . "
        };
      </script>";
  ?>
    
    <!-- React Results Container -->
    <div id="meal-plan-results"></div>

  <?php } ?>
</section>

<script type="text/babel">
  // Enhanced Meal Plan Form Component
  function MealPlanForm() {
    const [formData, setFormData] = React.useState({
      diet_type: '',
      calories: '',
      meals: ''
    });
    const [isSubmitting, setIsSubmitting] = React.useState(false);

    const handleInputChange = (e) => {
      setFormData({
        ...formData,
        [e.target.name]: e.target.value
      });
    };

    const handleSubmit = (e) => {
      setIsSubmitting(true);
      // Let the form submit naturally to PHP
      setTimeout(() => setIsSubmitting(false), 2000);
    };

    const dietOptions = [
      { value: '', label: '-- Choose a diet --' },
      { value: 'Anything', label: '🍽️ Anything' },
      { value: 'Keto', label: '🥑 Keto' },
      { value: 'Mediterranean', label: '🫒 Mediterranean' },
      { value: 'Paleo', label: '🥩 Paleo' },
      { value: 'Vegan', label: '🌱 Vegan' },
      { value: 'Vegetarian', label: '🥕 Vegetarian' }
    ];

    return (
      <div className="react-form-container">
        <div className="react-form">
          <h3 style={{ textAlign: 'center', marginBottom: '2rem', color: '#333' }}>
            🎯 Customize Your Meal Plan
          </h3>
          
          <form method="POST" action="" onSubmit={handleSubmit}>
            <div className="form-group">
              <label htmlFor="diet_type">Select Diet Type:</label>
              <select 
                name="diet_type" 
                id="diet_type" 
                className="form-control"
                value={formData.diet_type}
                onChange={handleInputChange}
                required
              >
                {dietOptions.map(option => (
                  <option key={option.value} value={option.value}>
                    {option.label}
                  </option>
                ))}
              </select>
            </div>

            <div className="form-group">
              <label htmlFor="calories">Daily Calorie Goal (kcal):</label>
              <input 
                type="number" 
                name="calories" 
                id="calories" 
                className="form-control"
                value={formData.calories}
                onChange={handleInputChange}
                required 
                min="1000" 
                step="50"
                placeholder="e.g., 2000"
              />
            </div>

            <div className="form-group">
              <label htmlFor="meals">Number of Meals:</label>
              <input 
                type="number" 
                name="meals" 
                id="meals" 
                className="form-control"
                value={formData.meals}
                onChange={handleInputChange}
                required 
                min="1" 
                max="6"
                placeholder="e.g., 3"
              />
            </div>

            <button type="submit" className="btn-generate" disabled={isSubmitting}>
              {isSubmitting && <span className="loading-spinner"></span>}
              {isSubmitting ? 'Generating Plan...' : '✨ Generate Plan'}
            </button>
          </form>
        </div>
      </div>
    );
  }

  // Enhanced Results Component
  function MealPlanResults() {
    const [data, setData] = React.useState(null);

    React.useEffect(() => {
      if (window.mealPlanData) {
        setData(window.mealPlanData);
      }
    }, []);

    if (!data) return null;

    return (
      <div>
        {/* Macronutrient Cards */}
        <section style={{ margin: '2rem 0' }}>
          <h2 style={{ textAlign: 'center', marginBottom: '2rem' }}>
            📊 Your Personalized Nutrition Breakdown
          </h2>
          
          <div style={{ 
            background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            padding: '2rem',
            borderRadius: '15px',
            color: 'white',
            textAlign: 'center',
            marginBottom: '2rem'
          }}>
            <h3>🎯 {data.diet} Diet Plan</h3>
            <p>Daily Target: {data.calories} kcal • {data.meals} meals per day</p>
          </div>

          <div className="macro-cards">
            <div className="macro-card">
              <h4>🍞 Carbohydrates</h4>
              <div className="macro-progress">
                <div 
                  className="macro-progress-bar carbs" 
                  style={{ width: `${data.macros.carbs.percentage}%` }}
                ></div>
              </div>
              <p><strong>{data.macros.carbs.grams}g</strong></p>
              <small>{data.macros.carbs.percentage}% of calories</small>
            </div>

            <div className="macro-card">
              <h4>🍗 Protein</h4>
              <div className="macro-progress">
                <div 
                  className="macro-progress-bar protein" 
                  style={{ width: `${data.macros.protein.percentage}%` }}
                ></div>
              </div>
              <p><strong>{data.macros.protein.grams}g</strong></p>
              <small>{data.macros.protein.percentage}% of calories</small>
            </div>

            <div className="macro-card">
              <h4>🥑 Fat</h4>
              <div className="macro-progress">
                <div 
                  className="macro-progress-bar fat" 
                  style={{ width: `${data.macros.fat.percentage}%` }}
                ></div>
              </div>
              <p><strong>{data.macros.fat.grams}g</strong></p>
              <small>{data.macros.fat.percentage}% of calories</small>
            </div>
          </div>
        </section>

        {/* Meal Suggestions */}
        <section className="meal-suggestions">
          <h2 style={{ textAlign: 'center', marginBottom: '2rem' }}>
            🍽️ Recommended Meals for Your {data.diet} Diet
          </h2>
          
          <div className="suggestions-grid">
            {data.sampleMeals.map((meal, index) => (
              <div key={index} className="meal-card">
                <img src={meal.img} alt={meal.name} className="meal-image" />
                <div className="meal-content">
                  <h3 className="meal-title">{meal.name}</h3>
                  <p className="meal-description">{meal.desc}</p>
                </div>
              </div>
            ))}
          </div>
        </section>
      </div>
    );
  }

  // Render components
  ReactDOM.render(<MealPlanForm />, document.getElementById('meal-plan-form'));
  
  // Only render results if data exists
  if (window.mealPlanData) {
    ReactDOM.render(<MealPlanResults />, document.getElementById('meal-plan-results'));
  }
</script>

<?php include 'footer.php'; ?>