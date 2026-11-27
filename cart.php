
<?php
require_once "config.php";

// Tambahkan produk ke keranjang
if (isset($_POST['add_to_cart'])) {
    $id = filter_input(
        INPUT_POST,
        'product_id',
        FILTER_VALIDATE_INT
    );

    if ($id) {
        $stmt = $pdo->prepare(
            "SELECT id FROM products WHERE id = ?"
        );
        $stmt->execute([$id]);

        if ($stmt->fetch()) {
            $_SESSION['cart'][$id] =
                ($_SESSION['cart'][$id] ?? 0) + 1;
        }
    }

    header("Location: cart.php");
    exit;
}

// Hapus produk dari keranjang
if (isset($_GET['remove'])) {
    $id = filter_input(
        INPUT_GET,
        'remove',
        FILTER_VALIDATE_INT
    );

    if ($id) {
        unset($_SESSION['cart'][$id]);
    }

    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;

if ($cart) {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $pdo->prepare(
        "SELECT * FROM products WHERE id IN ($placeholders)"
    );

    $stmt->execute($ids);
    $items = $stmt->fetchAll();

    foreach ($items as $item) {
        $total += $item['price'] * $cart[$item['id']];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - DENIM</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="products">
    <div class="section-heading">
        <h2>YOUR SHOPPING CART</h2>
    </div>

    <?php if (!$items): ?>

        <p>Keranjang belanja masih kosong.</p>

    <?php else: ?>

        <?php foreach ($items as $item): ?>

            <div class="product-info">
                <h3><?= htmlspecialchars($item['name']) ?></h3>

                <p>
                    Rp <?= number_format($item['price'], 0, ',', '.') ?>
                    x <?= $cart[$item['id']] ?>
                </p>

                <a href="cart.php?remove=<?= $item['id'] ?>">
                    Hapus
                </a>
            </div>

        <?php endforeach; ?>

        <h3>
            TOTAL: Rp <?= number_format($total, 0, ',', '.') ?>
        </h3>

        <br>

        <a href="checkout.php" class="btn">
            CHECKOUT NOW
        </a>

    <?php endif; ?>

    <br><br>
    <a href="index.php" class="text-link">← CONTINUE SHOPPING</a>
</div>

</body>
</html>
