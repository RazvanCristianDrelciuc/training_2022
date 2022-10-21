<?php

require_once 'common.php';

$sql = 'SELECT * FROM `items` WHERE order_id=?';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$items = $stmt->fetchAll();

?>

<?php require_once 'header.php' ?>

<div class="container">
    <?php foreach ($items as $item): ?>
        <table>
            <thead>
            <tr>
                <div class="productdetail">
                    <th><?= __('Title') ?>: <?php echo($item['title']); ?></th>
                    <th><?= __('Description') ?>: <?php echo($item['description']); ?></th>
                    <th><?= __('Price') ?>: <?php echo($item['price']); ?></th>
                </div>
            </tr>
            </thead>
        </table>
    <?php endforeach; ?>
    <a href="index.php">GO TO INDEX</a>
</div>

<?php require_once 'footer.php'; ?>
