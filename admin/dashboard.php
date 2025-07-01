<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

$users = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
$products = $pdo->query("SELECT COUNT(*) FROM product")->fetchColumn();
$orders = $pdo->query("SELECT COUNT(*) FROM `order`")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/admin.css">
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Overview</h2>
        <p>Total Users: <?php echo $users; ?></p>
        <p>Total Products: <?php echo $products; ?></p>
        <p>Total Orders: <?php echo $orders; ?></p>
    </main>
    <footer>
        <p>&copy; 2025 Mobile Store Admin</p>
    </footer>
</body>
</html>