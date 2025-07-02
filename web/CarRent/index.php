<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Car Rental Website</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    body {
      color: #333;
      background: #fff;
    }
    header {
      background: url("static/img/header_img.jpg") no-repeat center center/cover;
      padding: 100px 20px;
      color: white;
      text-align: center;
      position: relative;
    }
    header::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
    }
    header > div {
      position: relative;
      z-index: 2;
    }
    header h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
    }
    header p {
      font-size: 1.2rem;
    }
    .search-form {
      margin-top: 30px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .search-form input, .search-form button {
      padding: 10px;
      border-radius: 5px;
      border: none;
    }
    .search-form button {
      background: #007BFF;
      color: white;
      cursor: pointer;
    }
    .section {
      padding: 60px 20px;
      text-align: center;
    }
    .section h2 {
      font-size: 2rem;
      margin-bottom: 30px;
    }
    .fleet {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      & .img_div{
        height: 160px;
      }
    }
    .car-card {
      border: 1px solid #eee;
      border-radius: 10px;
      width: 250px;
      padding: 20px;
      text-align: left;
    }
    .car-card img {
      width: 100%;
      border-radius: 5px;
    }
    .features, .how-it-works {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      text-align: center;
    }
    .feature, .step {
      width: 200px;
    }
    .reviews {
      max-width: 800px;
      margin: 0 auto;
    }
    .review {
      background: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      text-align: left;
    }
    footer {
      background: #222;
      color: white;
      text-align: center;
      padding: 30px 20px;
    }
    footer a {
      color: #ccc;
      margin: 0 10px;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <header>
    <div>
      <h1>Drive Your Way</h1>
      <p>Flexible plans. Instant booking. Wide range of vehicles.</p>
      <!-- <div class="search-form">
        <input type="text" placeholder="Pickup Location">
        <input type="text" placeholder="Drop-off Location">
        <input type="date">
        <input type="time">
        <button>Find a Car</button>
      </div> -->
    </div>
  </header>

  <section class="section">
    <h2>Our Fleet</h2>
    <div class="fleet">
      <div class="car-card">
        <div class="img_div"><img src="static\img\rent_economy.jpg" alt="Economy"></div>
        <h3>Economy</h3>
        <p>$29/day • Manual • 4 seats</p>
        <button>Book Now</button>
      </div>
      <div class="car-card">
        <div class="img_div"><img src="static\img\rent_suv.jpg" alt="Economy"></div>
        <h3>SUV</h3>
        <p>$59/day • Auto • 5 seats</p>
        <button>Book Now</button>
      </div>
      <div class="car-card">
        <div class="img_div"><img src="static\img\rent_luxury.jpg" alt="Economy"></div>
        <h3>Luxury</h3>
        <p>$99/day • Auto • 5 seats</p>
        <button>Book Now</button>
      </div>
    </div>
  </section>

  <section class="section">
    <h2>Why Choose Us</h2>
    <div class="features">
      <div class="feature">
        <h3>✅ Free Cancellation</h3>
      </div>
      <div class="feature">
        <h3>✅ No Hidden Fees</h3>
      </div>
      <div class="feature">
        <h3>✅ 24/7 Assistance</h3>
      </div>
      <div class="feature">
        <h3>✅ Clean Cars</h3>
      </div>
    </div>
  </section>

  <section class="section">
    <h2>How It Works</h2>
    <div class="how-it-works">
      <div class="step">
        <h3>1. Search</h3>
      </div>
      <div class="step">
        <h3>2. Select</h3>
      </div>
      <div class="step">
        <h3>3. Book</h3>
      </div>
      <div class="step">
        <h3>4. Drive</h3>
      </div>
    </div>
  </section>

  <section class="section">
    <h2>Customer Reviews</h2>
    <div class="reviews">
      <div class="review">
        <p>⭐⭐⭐⭐⭐</p>
        <p>"Amazing service and clean cars. Highly recommended!" - Jane D.</p>
      </div>
      <div class="review">
        <p>⭐⭐⭐⭐</p>
        <p>"Very smooth booking process and affordable pricing." - Mark L.</p>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 DriveYourWay</p>
    <p>
      <a href="#">About</a>
      <a href="#">Contact</a>
      <a href="#">Privacy</a>
    </p>
    <p>
      Follow us:
      <a href="#">Facebook</a>
      <a href="#">Instagram</a>
      <a href="#">Twitter</a>
    </p>
  </footer>
</body>
</html>
