
<?php
require_once "config.php";

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DENIM STORE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <h2 class="logo">DENIM<span>.</span></h2>

        <ul class="nav-menu" id="navMenu">
            <li><a href="index.php">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#products">Collection</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>

        <div class="nav-right">
            <a href="cart.php" class="cart-btn">
                🛒 <?= array_sum($_SESSION['cart'] ?? []) ?>
            </a>
            <button class="menu-btn" onclick="toggleMenu()">☰</button>
        </div>
    </nav>
</header>

<section class="hero" id="home">
    <div class="hero-content">
        <p class="subtitle">NEW COLLECTION 2026</p>
        <h1>YOUR STYLE.<br>YOUR <span>DENIM.</span></h1>
        <p class="hero-description">
            Discover timeless denim fashion.
            Be confident and express your style.
        </p>
        <a href="#products" class="btn">SHOP NOW ↗</a>
    </div>

    <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?w=900"
             alt="Denim Fashion">
    </div>
</section>

<section class="about" id="about">
    <div class="about-image">
        <img src="https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800"
             alt="Denim outfit">
    </div>

    <div class="about-content">
        <p class="subtitle">OUR STORY</p>
        <h2>DENIM IS MORE<br>THAN JUST FASHION.</h2>
        <p>
            Denim represents freedom, confidence,
            and individuality. Find your own style with us.
        </p>
        <a href="#products" class="text-link">DISCOVER MORE →</a>
    </div>
</section>

<section class="products" id="products">
    <div class="section-heading">
        <p class="subtitle">OUR COLLECTION</p>
        <h2>FIND YOUR STYLE</h2>
        <p>Explore our latest denim collection.</p>
    </div>

    <div class="product-grid">

        <?php foreach ($products as $product): ?>

        <div class="product-card">

            <img
                src="<?= htmlspecialchars($product['image']) ?>"
                alt="<?= htmlspecialchars($product['name']) ?>"
            >

            <div class="product-info">
                <h3>
                    <?= htmlspecialchars($product['name']) ?>
                </h3>

                <p>
                    Rp <?= number_format($product['price'], 0, ',', '.') ?>
                </p>

                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id"
                           value="<?= $product['id'] ?>">

                    <button type="submit" name="add_to_cart">
                        ADD TO CART +
                    </button>
                </form>
            </div>

        </div>

        <?php endforeach; ?>

    </div>
</section>

<section class="banner">
    <div>
        <p>EXPRESS YOURSELF</p>
        <h2>DENIM NEVER<br>GOES OUT OF STYLE.</h2>
        <a href="#products" class="btn">EXPLORE NOW ↗</a>
    </div>
</section>

<section class="contact" id="contact">
    <p class="subtitle">GET IN TOUCH</p>
    <h2>LET'S TALK DENIM.</h2>
    <p>Follow us and discover more about our collection.</p>
    <a href="https://instagram.com" class="btn">
        FOLLOW INSTAGRAM ↗
    </a>
</section>

<footer>
    <h2>DENIM<span>.</span></h2>
    <p>© 2026 DENIM STORE. ALL RIGHTS RESERVED.</p>
</footer>

<script src="script.js"></script>

</body>
</html>
