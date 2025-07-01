<?php
require_once '../includes/config.php';
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: ../login.php');
    exit;
}

$error = '';
$edit_product = null;

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $image = $_FILES['image']['name'];

    if (!$name || !$description || $price === false || !$image) {
        $error = 'All fields are required, and price must be valid.';
    } else {
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $image);
        $stmt = $pdo->prepare("INSERT INTO product (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $image]);
        header('Location: products.php');
        exit;
    }
}

// Handle Edit Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $image = $_FILES['image']['name'];

    if (!$id || !$name || !$description || $price === false) {
        $error = 'All fields are required, and price must be valid.';
    } else {
        $stmt = $pdo->prepare("SELECT image FROM product WHERE id = ?");
        $stmt->execute([$id]);
        $current_image = $stmt->fetchColumn();

        $image_name = $current_image; // Retain old image if no new upload
        if ($image) {
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $image);
            $image_name = $image;
        }

        $stmt = $pdo->prepare("UPDATE product SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
        $stmt->execute([$name, $description, $price, $image_name, $id]);
        header('Location: products.php');
        exit;
    }
}

// Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $pdo->prepare("DELETE FROM product WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    header('Location: products.php');
    exit;
}

// Fetch Product for Editing
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM product WHERE id = ?");
    $stmt->execute([$_GET['edit_id']]);
    $edit_product = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$edit_product) {
        $error = 'Product not found.';
    }
}

// Fetch All Products
$stmt = $pdo->query("SELECT * FROM product");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/admin.css">
</head>
<body>
    <header>
        <h1>Manage Products</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="users.php">Users</a>
            <a href="products.php">Products</a>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2><?php echo $edit_product ? 'Edit Product' : 'Add Product'; ?></h2>
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($edit_product): ?>
                <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
                <input type="hidden" name="edit_product" value="1">
            <?php else: ?>
                <input type="hidden" name="add_product" value="1">
            <?php endif; ?>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>" required>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
            <label for="price">Price (NRS):</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $edit_product ? htmlspecialchars($edit_product['price']) : ''; ?>" required>
            <label for="image">Image:</label>
            <input type="file" id="image" name="image" accept="image/*" <?php echo $edit_product ? '' : 'required'; ?>>
            <?php if ($edit_product): ?>
                <p>Current Image: <?php echo htmlspecialchars($edit_product['image']); ?></p>
            <?php endif; ?>
            <button type="submit"><?php echo $edit_product ? 'Update Product' : 'Add Product'; ?></button>
        </form>
        <h2>Product List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>NRS <?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['image']); ?></td>
                    <td>
                        <a href="products.php?edit_id=<?php echo $product['id']; ?>"><button>Edit</button></a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="delete_id" value="<?php echo $product['id']; ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
    <footer>
        <p>© 2025 Mobile Store Admin</p>
    </footer>
</body>
</html>