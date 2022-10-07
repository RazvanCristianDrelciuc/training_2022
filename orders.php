<?php
require_once 'common.php';

$pdo = pdo_connect_mysql();

$sql3='SELECT orders.user_name, orders.details, orders.order_date, orders.total,ordered_products.title,
 ordered_products.description,ordered_products.price,ordered_products.qty FROM
 orders INNER JOIN ordered_products ON orders.id=ordered_products.id;';

$stmt=$pdo->query($sql3);
$orders = $stmt->fetchAll();

?>

<?php require 'header.php' ?>

<div class="container">
        <?php foreach($orders as $order): ?>
            <table>
                <thead>
                    <tr>
                        <div class="productdetail">
                            <th>Username: <?php echo($order['user_name']); ?></th>
                            <th>Details: <?php echo($order['details']); ?></th>
                            <th>Order Date:  <?php echo($order['order_date']); ?></th>
                        </div>
                            <th>Title: <?php echo($order['title']); ?></th>
                            <th>Description: <?php echo($order['description']); ?></th>
                            <th>Price:  <?php echo($order['price']); ?></th>
                            <th>Quantity:  <?php echo($order['qty']); ?></th>
                            <th>Total:  <?php echo($order['total']); ?></th>
                    </tr>
                </thead>
            </table>
        <?php endforeach; ?>
        <a href="test.php">GO TO INDEX</a>
</div>

<?php require_once 'footer.php'; ?>