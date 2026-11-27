
<?php
require_once "config.php";

$cart = $_SESSION['cart'] ?? [];

if (!$cart) {
    header("Location: cart.php");
    exit;
}

$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $pdo->prepare(
    "SELECT * FROM products WHERE id IN ($placeholders)"
);
$stmt->execute($ids);
$items = $stmt->fetchAll();

$total = 0;

foreach ($items as $item) {
    $total += $item['price'] * $cart[$item['id']];
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($name === '' || $phone === '' || $address === '') {
        $error = "Semua data wajib diisi.";
    } else {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare(
                "INSERT INTO orders
                (customer_name, phone, address, total)
                VALUES (?, ?, ?, ?)"
            );

            $stmt->execute([$name, $phone, $address, $total]);

            $orderId = $pdo->lastInsertId();

            $detail = $pdo->prepare(
                "INSERT INTO order_items
                (order_id, product_id, quantity, price)
                VALUES (?, ?, ?, ?)"
            );

            foreach ($items as $item) {
                $detail->execute([
                    $orderId,
                    $item['id'],
                    $cart[$item['id']],
                    $item['price']
                ]);
            }

            $pdo->commit();

            unset($_SESSION['cart']);

            header("Location: checkout.php?success=1");
            exit;

        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $error = "Pesanan gagal diproses. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - DENIM STORE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section class="products">

    <div class="section-heading">
        <p class="subtitle">DENIM STORE</p>
        <h2>CHECKOUT</h2>
    </div>

    <?php if (isset($_GET['success'])): ?>

        <h2>Pesanan berhasil dibuat!</h2>
        <p>Terima kasih sudah berbelanja di DENIM STORE.</p>
        <br>
        <a href="index.php" class="btn">BACK TO HOME</a>

    <?php else: ?>

        <?php if ($error): ?>
            <p><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <h3>
            Total Pembayaran:
            Rp <?= number_format($total, 0, ',', '.') ?>
        </h3>

        <br>

        <form method="POST">

            <label>Nama Lengkap</label>
            <input type="text" name="customer_name"
                   placeholder="Masukkan nama lengkap" required>

            <br><br>

            <label>Nomor Telepon</label>
            <input type="tel" name="phone"
                   placeholder="08xxxxxxxxxx" required>

            <br><br>

            <label>Alamat Lengkap</label>
            <textarea name="address"
                      placeholder="Masukkan alamat lengkap"
                      required></textarea>

            <br><br>

            <button type="submit" class="btn">
                PLACE ORDER
            </button>

        </form>

    <?php endif; ?>

</section>

</body>
</html>
