<?php
require_once 'includes/config.php';
require_once 'includes/db_connect.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']);

    if ($is_admin) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$identifier]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && $admin['password'] === $password) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: admin/dashboard.php');
            exit;
        } else {
            $error = "Invalid admin credentials";
        }
    } else {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ? OR email = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: profile.php');
            exit;
        } else {
            $error = "Invalid user credentials";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
</head>
<body>
    <header>
        <h1>Login</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="register.php">Register</a>
        </nav>
    </header>
    <main>
        <form id="loginForm" method="POST">
            <label for="identifier">Username or Email:</label>
            <input type="text" id="identifier" name="identifier" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label>
                <input type="checkbox" name="is_admin"> Login as Admin
            </label>
            <button type="submit">Login</button>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
    </main>
    <footer>
        <p>&copy; 2025 Mobile Store</p>
    </footer>
    <script src="<?php echo ASSETS_URL; ?>js/script.js"></script>
</body>
</html>