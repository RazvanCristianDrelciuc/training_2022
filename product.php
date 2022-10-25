<?php

require_once 'common.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'SELECT * FROM `products` WHERE id=?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $products = $stmt->fetch();
}

$case = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit_product'])) {
        $id = $_GET['id'];
        $sql = 'UPDATE  products  SET title=?, description=?, price=?, product_image=? WHERE id=?';
        $case = 1;
    } else if (isset($_POST['add_product'])) {
        $sql = 'INSERT INTO `products` (title, description, price, product_image) VALUES( ?, ?, ?,?)';
        $case = 2;
    }
    $id = $_GET['id'];
    $stmt = $pdo->prepare($sql);
}

switch ($case) {
    case 1:
        $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_POST['product_image'], $id]);
        header('Location: products.php');
        exit;
    case 2:
        $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_POST['product_image']]);
        header('Location: products.php');
        exit;
}

?>

<?php require_once 'header.php' ?>

<div class="formular">
    <form action="product.php<?= isset($_GET['id']) ? '?id=' . $_GET['id'] . '' : '' ?>" method="post">
        <?= __('Nume Produs') ?>: <input type="text" name="product_name" value="<?= isset($_GET['id']) ? $products['title'] : "" ?>"><br>
        <br>
        <?= __('Descriere Produs') ?>: <input type="text" name="description" value="<?= isset($_GET['id']) ? $products['description'] : "" ?>"><br>
        <br>
        <?= __('Pret') ?>: <input type="number" name="price" value="<?= isset($_GET['id']) ? $products['price'] : "" ?>"><br>
        <br>
        <?= __('Image') ?>:
        <div class="prodimage">
            <img src="images/<?= isset($_GET['id']) ? $products['product_image'] : "" ?>">
        </div>
        <br>
        <?= __('Image') ?>: <input type="file" name="product_image">
        <br><br>
            <input type="submit" name="edit_product" value="Edit Product">
        <br>
            <input type="submit" name="add_product" value="Add Product">
    </form>
</div>

<?php require_once 'footer.php'; ?>
