<?php

require_once 'common.php';

isAdmin();

if (isset($_GET['id'])) {
    //$id = $_GET['id'];
    $sql = 'SELECT * FROM `products` WHERE id=?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
}

$case = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['edit_product'])) {
        $id = $_GET['id'];
        $sql = 'UPDATE  products  SET title=?, description=?, price=?, product_image=? WHERE id=?';
        $image_file = $_FILES["product_image"];
        move_uploaded_file(
            $image_file["tmp_name"],
            __DIR__ . "/images/" . $image_file['name']
        );
        $case = 'edit';
    } else if (isset($_POST['add_product'])) {
        $image_file = $_FILES["product_image"];
        move_uploaded_file(
            $image_file["tmp_name"],
            __DIR__ . "/images/" . $image_file['name']
        );
        $sql = 'INSERT INTO `products` (title, description, price, product_image) VALUES ( ?, ?, ?,?)';
        $case = 'add';
    }
    $id = $_GET['id'];
    $stmt = $pdo->prepare($sql);
}

switch ($case) {
    case 'edit':
        $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_POST['product_image'], $id]);
        header('Location: products.php');
        exit;
    case 'add':
        $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_POST['product_image']]);
        header('Location: products.php');
        exit;
}

?>

<?php require_once 'header.php' ?>

<div class="form">
    <form action="product.php<?= isset($_GET['id']) ? '?id=' . $_GET['id'] . '' : '' ?>" method="post" >
        <?= __('Nume Produs') ?>: <input type="text" name="product_name" value="<?= $product['title'] ?? "" ?>"><br>
        <br>
        <?= __('Descriere Produs') ?>: <input type="text" name="description" value="<?= $product['description'] ?? "" ?>"><br>
        <br>
        <?= __('Pret') ?>: <input type="number" name="price" value="<?= $product['price'] ?? "" ?>"><br>
        <br>
        <?= __('Image') ?>:
        <div class="prodimage">
            <img src="images/<?= $product['product_image'] ?? "" ?>">
        </div>
        <br>
        <?= __('Image') ?>:<input type="file" name="product_image" >
        <br><br>
            <input type="submit" name="edit_product" value="<?= __('Edit product') ?>">
        <br>
            <input type="submit" name="add_product" value="<?= __('Add product') ?>">
    </form>
</div>

<?php require_once 'footer.php'; ?>
