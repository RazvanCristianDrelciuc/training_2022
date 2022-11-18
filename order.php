<?php

require_once 'common.php';

redirectIfNotAdmin();

$sql = 'SELECT * FROM `items` WHERE order_id = ?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$items = $stmt->fetchAll();

$sql2 = 'SELECT * from orders WHERE id = ?';
$stmt = $pdo->prepare($sql2);
$stmt->execute([$_GET['id']]);
$order = $stmt->fetch();

?>

<?php require_once 'header.php' ?>

<div class="container">
    <div class="productdetail">
        <th><?= __('Username') ?> : <?= $order['user_name']; ?></th>
        <th><?= __('Details') ?> : <?= $order['details']; ?></th>
        <th><?= __('Order date') ?> : <?= $order['order_date']; ?></th>
    </div>
    <?php foreach ($items as $item): ?>
        <table>
            <thead>
            <tr>
                <div class="productdetail">
                    <th><?= __('Title') ?> : <?= $item['title']; ?></th>
                    <th><?= __('Description') ?> : <?= $item['description']; ?></th>
                    <th><?= __('Price') ?> : <?= $item['price']; ?></th>
                </div>
            </tr>
            </thead>
        </table>
    <?php endforeach; ?>
    <a href="index.php"><?= __('GO TO INDEX') ?></a>
</div>

<?php require_once 'footer.php'; ?>
