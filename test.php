<?php

require_once 'common.php';

$pdo = pdo_connect_mysql();

if(empty($_SESSION['cart'])) {
    $sql2 = "SELECT * FROM products";
    $stmt=$pdo->prepare($sql2);
    $stmt->execute();
    $products = $stmt->fetchAll();
} else {
    $excludeIds=array_values(array_keys($_SESSION['cart']));
    $ct=count($excludeIds);
    for($i=0;$i<$ct;$i++){
        $in[]='?';
    }    
    $in=implode(', ',$in);
    $sql2='SELECT * FROM products WHERE id NOT IN (' . $in . ')';
    $stmt = $pdo->prepare($sql2, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute($excludeIds);
    $products = $stmt->fetchAll();
}


///Add to cart
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_POST['product_id']]);
    $products = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($products && $quantity > 0) {
        // Product exists in database, now we can create/update the session variable for the cart
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])){
            if (array_key_exists($product_id, $_SESSION['cart'])){
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = $quantity;}
            } else{
            $_SESSION['cart'] = array($product_id => $quantity);}
    }
    header('location: test.php');
    exit;
}

?>

<?php require 'header.php' ?>


    <div class="container">
    <?php foreach($products as $product): ?>
        <table>
            <thead>
                <tr>
                    <?php $quantity=0 ?>
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
                        <form action="test.php" method="POST">
                            <input type="number" name="quantity" value="1" min="1" max="10" placeholder="Quantity" required>
                            <input type="hidden" name="product_id" value="<?=$product['id']?>">
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
