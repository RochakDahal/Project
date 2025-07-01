<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT o.*, p.name FROM `order` o JOIN product p ON o.user_id = p.id WHERE o.user_id = ?");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
</head>
<body>
    <header>
        <h1>Profile</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Welcome, <?php echo $user['username']; ?></h2>
        <p>Email: <?php echo $user['email']; ?></p>
        <h3>Your Orders</h3>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Payment Method</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['name']; ?></td>
                    <td>NRS <?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['payment_method']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Mobile Store</p>
    </footer>
    <script src="<?php echo ASSETS_URL; ?>js/script.js"></script>
</body>
</html>