<?php

require_once 'common.php';

$sql = "SELECT * FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();

if (isset($_POST['product_id'])) {
    $product_remove = (int)$_POST['product_id'];
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$product_remove]);
    header('Location: products.php');
}

?>

<?php require_once 'header.php' ?>

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
                    <form action="products.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">REMOVE</button>
                        <a href="product.php?id=<?= $product['id'] ?>">EDIT PRODUCT</a>
                    </form>
                </th>
            </tr>
            </thead>
        </table>
    <?php endforeach; ?>
    <a href="product.php">Add product</a>
    <a href="cart.php">Go To Cart</a>
</div>

<?php require_once 'footer.php'; ?>
