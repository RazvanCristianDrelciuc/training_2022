<?php

require_once 'common.php';

if (!empty($_SESSION['cart'])) {
    $excludeIds = array_values(array_keys($_SESSION['cart']));
    $in = array_fill(0, count($excludeIds), '?');
    $in = implode(', ', $in);
    $sql = 'SELECT * FROM products WHERE id  IN (' . $in . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($excludeIds);
    $products = $stmt->fetchAll();
}

?>

<?php require_once 'header.php'; ?>

    <div class="container">
        <?php foreach ($products as $product) : ?>
            <div class="prodimage">
                <img src="images/<?= $product['product_image'] ?>">
            </div>
            <div class="productdetail">
                <ul>
                    <li> <?= $product['title'] ?></li>
                    <li> <?= $product['description'] ?></li>
                    <li> <?= $product['price'] ?> </li>
                    <li> <?= $_SESSION['cart'][$product['id']] ?></li>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

<?php require_once 'footer.php'; ?>