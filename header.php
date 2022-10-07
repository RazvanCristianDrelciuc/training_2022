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
        <li><a href="cart.php">CART</a></li>
        <li><a href="test.php">INDEX</a></li>
        <li><a href="login.php">LOGIN</a></li>
        <?php if($_SESSION['admin'] == 1) { ?>
                    <li><a href="products.php">PRODUCTS</a></li>
                    <li><a href="product.php">PRODUCT</a></li>
                    <li><a href="orders.php">ORDERS</a></li>
        <?php } ?>
    </ul>
</div>