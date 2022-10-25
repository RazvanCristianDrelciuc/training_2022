<?php

require_once 'common.php';

if (empty($_SESSION['cart'])) {
    $sql = 'SELECT * FROM products';
} else {
    $excludeIds = array_values(array_keys($_SESSION['cart']));
    $in = array_fill(0, count($excludeIds), '?');
    $in = implode(', ', $in);
    $sql = 'SELECT * FROM products WHERE id NOT IN (' . $in . ')';
}
$stmt = $pdo->prepare($sql);
$stmt->execute(isset($excludeIds) ? $excludeIds : NULL);
$products = $stmt->fetchAll();

if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_POST['product_id']]);
    $products = $stmt->fetch();
    if ($products && $quantity > 0) {
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (isset($product_id, $_SESSION['cart'])) {
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $quantity;
            }
        }
    }
    header('location: index.php');
    exit;
}

?>

<?php require 'header.php' ?>

<div class="container">
    <?php foreach ($products as $product): ?>
        <table>
            <thead>
            <tr>
                <div class="prodimage">
                    <img src="images/<?= $product['product_image'] ?>">
                </div>
                <div class="productdetail">
                    <th><?= __('ID') ?>: <?= $product['id'] ?></th>
                    <th><?= __('TITLE') ?>: <?= $product['title'] ?></th>
                    <th><?= __('DESCRIPTION') ?>: <?= $product['description'] ?></th>
                    <th><?= __('PRICE') ?>: <?= $product['price'] ?></th>
                </div>
                <th rowspan="3">
                    <form action="index.php" method="POST">
                        <input type="number" name="quantity" value="1" min="1" max="10" placeholder="Quantity" required>
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="submit" value="Add to Cart">
                    </form>
                </th>
            </tr>
            </thead>
        </table>
    <?php endforeach; ?>
    <a href="cart.php">Go To Cart</a>
</div>

<?php require_once 'footer.php'; ?>
