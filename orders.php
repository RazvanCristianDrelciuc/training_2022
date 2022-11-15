<?php

require_once 'common.php';

isAdmin();

$sql = 'SELECT * from orders';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll();

?>

<?php require_once 'header.php' ?>

    <div class="container">
        <?php foreach ($orders as $order): ?>
            <table>
                <thead>
                <tr>
                    <div class="productdetail">
                        <th><?= __('Username') ?>: <?php echo($order['user_name']); ?></th>
                        <th><?= __('Details') ?>: <?php echo($order['details']); ?></th>
                        <th><?= __('Order date') ?>: <?php echo($order['order_date']); ?></th>
                    </div>
                    <a href="order.php?id=<?= $order['id'] ?>"><?= __('View order') ?></a>
                </tr>
                </thead>
            </table>
        <?php endforeach; ?>
        <a href="index.php"><?= __('Go to index ') ?></a>
    </div>

<?php require_once 'footer.php'; ?>