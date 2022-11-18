<?php

require_once 'common.php';

if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {

    unset($_SESSION['cart'][$_POST['product_id']]);
    header('Location: cart.php');
    exit;
}

if (!empty($_SESSION['cart'])) {
    $excludeIds = array_values(array_keys($_SESSION['cart']));
    $in = array_fill(0, count($excludeIds), '?');
    $in = implode(', ', $in);
    $sql = 'SELECT * FROM products WHERE id  IN (' . $in . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($excludeIds);
    $products = $stmt->fetchAll();
}

$name = $details = $comments = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = [];
    if (empty($_POST['name'])) {
        $error['nameErr'] = 'Name is req';
    }
    if (empty($_POST['comments'])) {
        $error['commentsErr'] = 'Name is req';
    }
    if (empty($_POST['details'])) {
        $error['detailsErr'] = 'Name is req';
    }

    $name = testInput($_POST['name']);
    $comments = testInput($_POST['comments']);
    $details = testInput($_POST['details']);
}

if (isset($_POST['checkout']) && empty($error)) {
    $total = 0;
    foreach ($products as $product) {
        $total += $product['price'] * $_SESSION['cart'][$product['id']];
    }
    $orderDate = date('Y-m-d h:i:sa');

    $sql = 'INSERT INTO `orders` (user_name, details, order_date, total) VALUES (?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['name'], $_POST['details'], $orderDate, $total]);

    $lastId = $pdo->lastInsertId();
    $sql2 = 'INSERT INTO `items` (order_id, title, description, price) VALUES (?, ?, ?, ?)';
    $stmt2 = $pdo->prepare($sql2);
    foreach ($products as $product) {
        $stmt2->execute([$lastId, $product['title'], $product['description'], $product['price']]);
    }

    $emailTo = MANAGER_EMAIL;
    $subject = 'New order placed';
    $headers = [
        __('From') =>__( 'demo mail <razvandrelciuc@gmail.com>'),
        __('Content-Type') => __('text/html; charset=ISO-8859-1'),
        _('MIME-Version') => '1.0',
    ];

    ob_start();
    include 'template.php';
    $message = ob_get_clean();

    mail($emailTo, $subject, $message, $headers);

    unset($_SESSION['cart']);
    header('Location: index.php');
    exit;
}
?>

<?php require_once 'header.php' ?>

<?php if (empty($products)): ?>
    <h1><?= __('You have no products added to cart') ?>!</h1>
<?php else: ?>
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
                        <form action="cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit"><?= __('Remove') ?></button>
                        </form>
                    </th>
                </tr>
                </thead>
            </table>
        <?php endforeach; ?>
        <a href="index.php"><?= __('Go to index') ?></a>
    </div>
<?php endif; ?>
<div class="form">
    <form action="cart.php" method="post">
        <?= __('Name') ?> <input type="text" name="name" value="<?= $name ?>"><br>
        <?php if (!empty($error['nameErr'])): ?>
            <span>*<?= __($error['nameErr']); ?></span>
        <?php endif; ?>
        <br>
        <?= __('Contact Details') ?> <input type="text" name="details" value="<?= $details ?>"><br>
        <?php if (!empty($error['detailsErr'])) : ?>
            <span>*<?= __($error['detailsErr']); ?></span>
        <?php endif; ?> <br>
        <?= __('Comments') ?>: <input type="text" name="comments" value="<?= $comments ?>"><br>
        <?php if (!empty($error)): ?>
            <span>*<?= __($error['commentsErr']); ?></span>
        <?php endif; ?> <br>
        <a href="index.php"><?= __('Back to Index') ?></a>
        <input type="submit" name="checkout" value="<?= __('Checkout') ?>">
        <p><?= __('* requierd field') ?></p>
    </form>
</div>

<?php require_once 'footer.php'; ?>
