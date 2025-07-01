<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';
require_once 'includes/esewa_config.php';

if (isset($_GET['q']) && $_GET['q'] === 'su' && isset($_GET['data'])) {
    $payload = json_decode(base64_decode($_GET['data']), true);
    $data = "total_amount={$payload['total_amount']},transaction_uuid={$payload['transaction_uuid']},product_code=" . ESEWA_MERCHANT_ID;
    $signature = generateSignature($data, ESEWA_SECRET_KEY);

    if ($signature === $payload['signature']) {
        $stmt = $pdo->prepare("UPDATE payment SET status = 'completed', ref_id = ? WHERE transaction_uuid = ?");
        $stmt->execute([$payload['ref_id'], $payload['transaction_uuid']]);
        $stmt = $pdo->prepare("UPDATE `order` SET status = 'completed' WHERE id = (SELECT order_id FROM payment WHERE transaction_uuid = ?)");
        $stmt->execute([$payload['transaction_uuid']]);
        header('Location: profile.php');
        exit;
    } else {
        $error = "Payment verification failed";
    }
} elseif (isset($_GET['q']) && $_GET['q'] === 'fu') {
    $error = "Payment failed or was cancelled";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
</head>
<body>
    <header>
        <h1>Payment Verification</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="profile.php">Profile</a>
        </nav>
    </header>
    <main>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php else: ?>
            <p>Processing payment...</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2025 Mobile Store</p>
    </footer>
</body>
</html>