<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>mySHOP</title>
</head>
<body>

<div class="nav">
    <ul>
        <li><a href="cart.php"><?= __('Cart') ?></a></li>
        <li><a href="index.php"><?= __('Index') ?></a></li>
        <li><a href="login.php"><?= __('Login') ?></a></li>
        <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) { ?>
            <li><a href="products.php"><?= __('Products') ?></a></li>
            <li><a href="product.php"><?= __('Product') ?></a></li>
            <li><a href="orders.php"><?= __('Orders') ?></a></li>
        <?php } ?>
    </ul>
</div>