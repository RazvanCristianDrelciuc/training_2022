<?php
require_once 'common.php';

$pdo = pdo_connect_mysql();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'SELECT * FROM `products` WHERE id=?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $products = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['edit_product'])){
    $id=$_GET['id'];
    $newTitle=$_POST['product_name'];
    $newDesc=$_POST['description'];
    $newPrice=$_POST['price'];
    $newImage=$_POST['product_image'];
    $sql = 'UPDATE  products  SET title=?, description=?, price=?, product_image=? WHERE id=?';
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$newTitle, $newDesc, $newPrice, $newImage, $id]);

    header('Location: products.php');
    exit;
}

if(isset($_POST['add_product'])){
    $newTitle=$_POST['product_name'];
    $newDesc=$_POST['description'];
    $newPrice=$_POST['price'];
    $newImage=$_POST['product_image'];
    $sql = 'INSERT INTO `products` (title, description, price, product_image) VALUES( ?, ?, ?,?)';
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$newTitle, $newDesc, $newPrice, $newImage]);

    header('Location: products.php');
    exit;
}

?>

<?php require 'header.php' ?>
<div class="formular">
        <form action="product.php<?= isset($_GET['id']) ? '?id=' . $_GET['id'] . '' : '' ?>" method="post">
            Nume produs: <input type="text" name="product_name" value="<?= isset($_GET['id']) ? $products['title'] : ""?>"><br>
            <span >*</span>
            <br>
            Descriere Produs: <input type="text" name="description" value="<?= isset($_GET['id']) ? $products['description'] : "" ?>"><br>
            <span >*</span>
            <br>
            Pret: <input type="number" name="price" value="<?=isset($_GET['id']) ? $products['price'] : ""?>"><br>
            <span >*</span>
            <br>
            Imagine: 
           <div class="prodimage">
                            <img src="images/<?= isset($_GET['id']) ? $products['product_image'] : ""?>">
            </div>
            <br>
            <br>
            Image: <input type="file" name="product_image" >
            <span >*</span>
            <br><br>
            <input type="submit" name="edit_product" value="Edit Product">
            <br>
            <input type="submit" name="add_product" value="Add Product">
            <p>* required field</p>
        </form>
</div>

<?php require_once 'footer.php'; ?>
