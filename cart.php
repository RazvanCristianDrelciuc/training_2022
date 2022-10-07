<?php
require_once 'common.php';

$pdo = pdo_connect_mysql();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart']=array();
}

if(!empty($_SESSION['cart'])){
    $excludeIds=array_values(array_keys($_SESSION['cart']));
    $ct=count($excludeIds);
    for($i=0;$i<$ct;$i++){
        $in[]='?';


    }
    $in=implode(', ',$in);
    $sql2='SELECT * FROM products WHERE id  IN (' . $in . ')';
    $stmt = $pdo->prepare($sql2, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute($excludeIds);
    $products = $stmt->fetchAll();
    }

if(isset($_GET['remove']) && is_numeric($_GET['remove']) &&
    isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    
    unset($_SESSION['cart'][$_GET['remove']]);
    header('location: cart.php');
    exit;
}

//FORM VALIDATION
$name=$comments=$details="";
$nameErr=$commentsErr=$detailsErr="";

if($_SERVER["REQUEST_METHOD"]== "POST" ){
    $verif=0;
    if(empty($_POST["name"])){
        $nameErr="Name is required";
        $verif=1;
    }else {
        $name = test_input($_POST["name"]);
        if(!preg_match("/^[a-zA-Z-' ]*$/",$name)){
            $nameErr="Only letters and white space allowed";
        }
    }

    if(empty($_POST["comments"])){
        $commentsErr="Comments is required";
        $verif=1;
    }else {
        $comments = test_input($_POST["comments"]);
    }

    if(empty($_POST["details"])){
        $detailsErr="Details are required";
        $verif=1;
    }else {
        $details = test_input($_POST["details"]);
    }
}

if(isset($_POST['checkout']) && $verif==0){
    $total = 0;
    foreach($products as $product) {
        $total += $product['price'] * $_SESSION['cart'][$product['id']];
    }
    $orderDate = date('Y-m-d h:i:sa');

    $sql = 'INSERT INTO `orders` (user_name, details, order_date, total,id) VALUES(?, ?, ?, ?,?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST['name'], $_POST['details'], $orderDate,$total,$_SESSION['user_id']]);
    
    $sql2 = 'INSERT INTO `ordered_products` (id, title, description, price,qty) VALUES(?, ?, ?, ?,?)';
    $stmt2 = $pdo->prepare($sql2);
    foreach($products as $product) {
            $productId = $_SESSION['user_id'];
            $productTitle =  $product['title'];
            $productDescription = $product['description'];
            $productPrice = $product['price'];
            $quantity = $_SESSION['cart'][$product['id']];
            $stmt2->execute([$productId, $productTitle, $productDescription, $productPrice,$quantity]);
    }
    /* $emailTo = MANAGER_EMAIL;
    $subject = 'New order placed';
    
    $headers = "From: demo mail <razvandrelciuc@gmail.com>\r\n";
    $headers .= "MIME-Version:1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // ob_start();
    // include 'template.php';
    // $message = ob_get_clean();


    mail($emailTo, $subject, $message, $headers);
    */
    unset($_SESSION['cart']);
    header('Location: test.php');
    exit;
}

?>

<?php require 'header.php' ?>

    <?php if (empty($products)): ?>
    <h1>You have no products added to cart!</h1>
    <?php else: ?>

    <div class="container">
        <?php foreach($products as $product): ?>
            <table>
                <thead>
                    <tr>
                        <div class="prodimage">
                            <img src="images/<?= $product['product_image']?>">
                        </div>
                        <div class="productdetail">
                            <th>ID <?php echo($product['id']); ?></th>
                            <th>TITLE <?php echo($product['title']); ?></th>
                            <th>DESCRIPTION <?php echo($product['description']); ?></th>
                            <th>PRICE <?php echo($product['price']); ?></th>
                        </div>
                        <th rowspan="3">
                            <form action="cart.php" method="POST">
                                <a href="cart.php?remove=<?=$product['id']?>" class="remove">Remove</a>
                            </form>
                        </th>
                    </tr>
                </thead>
            </table>
        <?php endforeach; ?>
        <a href="test.php">GO TO INDEX</a>
    </div>
    <?php endif; ?>
    <div class="formular">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            Name: <input type="text" name="name" value="<?php echo $name?>"><br>
            <span >*<?php echo $nameErr;?></span>
            <br>
            Contact Details: <input type="text" name="details" value="<?php echo $details?>"><br>
            <span >*<?php echo $detailsErr;?></span>
            <br>
            Comments: <input type="text" name="comments" value="<?php echo $comments?>"><br>
            <span >*<?php echo $commentsErr;?></span>
            <br>
            <a href="test.php">Back to Index</a>
            <input type="submit" name="checkout" value="Checkout">
            <p>* required field</p>
        </form>
    </div>

<?php require_once 'footer.php'; ?>
