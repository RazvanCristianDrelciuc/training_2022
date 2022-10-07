<?php

include_once 'common.php';
$pdo = pdo_connect_mysql();

    $excludeIds=array_values(array_keys($_SESSION['cart']));
    $ct=count($excludeIds);
    for($i=0;$i<$ct;$i++){
        $in[]='?';
    }

    $in=implode(', ',$in);
    $sql2='SELECT * FROM products WHERE id IN (' . $in . ')';
    $stmt = $pdo->prepare($sql2, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute($excludeIds);
    $products = $stmt->fetchAll();

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
                        <li> <?= $_SESSION['cart'][$product['id']]?></li>
                    </ul>
                </div>
        <?php endforeach; ?>
    </div>


<?php require_once 'footer.php'; ?>