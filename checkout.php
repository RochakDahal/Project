<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

// Check if esewa_config.php exists
if (!file_exists('includes/esewa_config.php')) {
    die('Error: esewa_config.php not found in includes directory.');
}
require_once 'includes/esewa_config.php';

if (!isLoggedIn()) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate POST data
    if (!isset($_POST['product_id']) || !isset($_POST['quantity']) || !isset($_POST['payment_method'])) {
        $error = 'All fields are required.';
    } elseif (!in_array($_POST['payment_method'], ['esewa', 'cod'])) {
        $error = 'Invalid payment method selected.';
    } else {
        $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
        $payment_method = $_POST['payment_method'];

        if ($product_id === false || $quantity <= 0) {
            $error = 'Invalid product or quantity.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($product) {
                $total_amount = $product['price'] * $quantity;
                $transaction_uuid = uniqid();

                try {
                    $stmt = $pdo->prepare("INSERT INTO `order` (user_id, total_amount, payment_method) VALUES (?, ?, ?)");
                    $stmt->execute([$_SESSION['user_id'], $total_amount, $payment_method]);
                    $order_id = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO payment (order_id, transaction_uuid, amount, status) VALUES (?, ?, ?, 'pending')");
                    $stmt->execute([$order_id, $transaction_uuid, $total_amount]);

                    if ($payment_method === 'esewa') {
                        $data = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=" . ESEWA_MERCHANT_ID;
                        $signature = generateSignature($data, ESEWA_SECRET_KEY);
                        ?>
                        <form id="esewaForm" action="<?php echo ESEWA_URL; ?>" method="POST">
                            <input type="hidden" name="amount" value="<?php echo $total_amount; ?>">
                            <input type="hidden" name="tax_amount" value="0">
                            <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                            <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
                            <input type="hidden" name="product_code" value="<?php echo ESEWA_MERCHANT_ID; ?>">
                            <input type="hidden" name="product_service_charge" value="0">
                            <input type="hidden" name="product_delivery_charge" value="0">
                            <input type="hidden" name="success_url" value="<?php echo SUCCESS_URL; ?>">
                            <input type="hidden" name="failure_url" value="<?php echo FAILURE_URL; ?>">
                            <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
                            <input type="hidden" name="signature" value="<?php echo $signature; ?>">
                        </form>
                        <script>
                            document.getElementById('esewaForm').submit();
                        </script>
                        <?php
                        exit;
                    } else {
                        header('Location: ' . BASE_URL . 'profile.php');
                        exit;
                    }
                } catch (PDOException $e) {
                    $error = 'Database error: ' . $e->getMessage();
                }
            } else {
                $error = 'Product not found.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
</head>
<body>
    <header>
        <h1>Checkout</h1>
        <nav>
            <a href="<?php echo BASE_URL; ?>index.php">Home</a>
            <a href="<?php echo BASE_URL; ?>profile.php">Profile</a>
        </nav>
    </header>
    <main>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form id="checkoutForm" method="POST">
            <input type="hidden" name="product_id" value="<?php echo isset($_POST['product_id']) ? htmlspecialchars($_POST['product_id']) : ''; ?>">
            <input type="hidden" name="quantity" value="<?php echo isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : ''; ?>">
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="esewa" <?php echo isset($_POST['payment_method']) && $_POST['payment_method'] === 'esewa' ? 'selected' : ''; ?>>eSewa</option>
                <option value="cod" <?php echo isset($_POST['payment_method']) && $_POST['payment_method'] === 'cod' ? 'selected' : ''; ?>>Cash on Delivery</option>
            </select>
            <button type="submit">Proceed to Payment</button>
        </form>
    </main>
    <footer>
        <p>© 2025 Mobile Store</p>
    </footer>
    <script src="<?php echo ASSETS_URL; ?>js/script.js"></script>
</body>
</html>