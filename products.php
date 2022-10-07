<?php
require_once 'common.php';

$pdo = pdo_connect_mysql();

$sql2 = "SELECT * FROM products";

$stmt = $pdo->prepare($sql2, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
$stmt->execute();
$products = $stmt->fetchAll();

if(isset($_POST['product_id']) ){
    $product_remove= (int)$_POST['product_id'];
    $stmt=$pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$product_remove]);

    header('Location: products.php');
 //   $pdo->exec($stmt);
}

?>

<?php require 'header.php' ?>

<div class="container">
    <?php foreach($products as $product): ?>
        <?php var_dump($product['product_image']); ?>
        <table>
            <thead>
                <tr>
                    <div class="prodimage">
                        <img src="images/<?= $product['product_image']?>">
                    </div>
                    <div class="productdetail">
                        <th>ID <?php echo($product['id']); ?></th>
                        <th>TITLE <?php echo($product['title']); ?></th>
                        <th>DESCRIPTION <?php echo($product['description']); ?></th>
                        <th>PRICE <?php echo($product['price']); ?></th>
                    </div>
                    <th rowspan="3">
                        <form action="products.php" method="POST">
                            <input type="hidden" name="product_id" value="<?=$product['id']?>">
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
