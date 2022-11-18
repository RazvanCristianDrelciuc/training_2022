<?php

require_once 'common.php';

redirectIfNotAdmin();

if (isset($_GET['id'])) {
    $sql = 'SELECT * FROM `products` WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_GET['id']]);
    $product = $stmt->fetch();
}

$error = [];
$productName = '';
$description = '';
$price = '';
$case = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['product_name'])) {
        $error['nameErr'] = 'Product name is req';
    }
    if (empty($_POST['description'])) {
        $error['descriptionErr'] = 'Description is req';
    }
    if (empty($_POST['price'])) {
        $error['priceErr'] = 'Price is req';
    }

    $productName = testInput($_POST['product_name']);
    $description = testInput($_POST['description']);
    $price = testInput($_POST['price']);

    if (!empty($error)) {
        $case = '';
    } else if (isset($_GET['id'])) {
        $sql = 'UPDATE  products  SET title = ?, description = ?, price = ?, product_image = ? WHERE id = ?';
        $case = 'edit';
        $stmt = $pdo->prepare($sql);
    } else {
        $sql = 'INSERT INTO `products` (title, description, price, product_image) VALUES ( ?, ?, ?,? )';
        $case = 'add';
        $stmt = $pdo->prepare($sql);
    }

    if ($case == 'add' || $case = 'edit') {
        $targetDir = __DIR__ . '/images/';
        $targetFile = $targetDir . basename($_FILES['fileToUpload']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
        if ($check === false) {
            $error['imageErr'] = 'File not image';
        }
        if (file_exists($targetFile)) {
            $error['imageErr'] = 'File not image';
        }
        $type = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($imageFileType, $type)) {
            $error['imageErr'] = 'File image wrong';
        }

        if (empty($error)) {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $targetFile);
        }
    }
}

switch ($case) {
    case 'edit':
        if (!empty($_FILES['fileToUpload']['name'])) {
            $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_FILES['fileToUpload']['name'], $_GET['id']]);
        } else {
            $stmt = $pdo->prepare('UPDATE  products  SET title = ?, description = ?, price = ? WHERE id = ?');
            $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_GET['id']]);
        }
        header('Location: products.php');
        break;
    case 'add':
        $stmt->execute([$_POST['product_name'], $_POST['description'], $_POST['price'], $_FILES['fileToUpload']['name']]);
        header('Location: products.php');
        break;
}

?>

<?php require_once 'header.php' ?>

<div class="form">
    <form action="product.php<?= isset($_GET['id']) ? '?id=' . $_GET['id'] . '' : '' ?>" method="post"
          enctype="multipart/form-data">
        <?= __('Nume Produs') ?>: <input type="text" name="product_name"
                                         value="<?= $product['title'] ?? $productName ?>"><br>
        <?php if (!empty($error['nameErr'])): ?>
            <span>*<?= __($error['nameErr']); ?></span>
        <?php endif; ?>
        <br>
        <?= __('Descriere Produs') ?>: <input type="text" name="description"
                                              value="<?= $product['description'] ?? $description ?>"><br>
        <?php if (!empty($error['descriptionErr'])): ?>
            <span>*<?= __($error['descriptionErr']); ?></span>
        <?php endif; ?>
        <br>
        <?= __('Pret') ?>: <input type="number" name="price" value="<?= $product['price'] ?? $price ?>"><br>
        <?php if (!empty($error['priceErr'])): ?>
            <span>*<?= __($error['priceErr']); ?></span>
        <?php endif; ?>
        <br>
        <?= __('Image') ?>:
        <div class="prodimage">
            <img src="images/<?= $product['product_image'] ?? "" ?>">
        </div>
        <br>
        <?= __('Image') ?>:<input type="file" name="fileToUpload" id="fileToUpload"
                                  value="<?= $product['product_image'] ?? "" ?>">
        <br><br>
        <input type="submit" name="edit_product"
               value="<?= isset($_GET['id']) ? __('Edit product') : __('Add product') ?>">
    </form>
</div>

<?php require_once 'footer.php'; ?>
