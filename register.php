<?php

require_once 'common.php';

$pdo = pdo_connect_mysql();

$user_name=$password="";
$user_nameErr=$passwordErr=$alreadyExistsErr="";
$verif=0;
if($_SERVER["REQUEST_METHOD"]== "POST" ){
    $verif=0;
    if(empty($_POST["user_name"])){
        $user_nameErr="Name is required";
        $verif=1;
    }else {
        $user_name = test_input($_POST["user_name"]);
        if(!preg_match("/^[a-zA-Z-' ]*$/",$user_name)){
            $user_nameErr="Only letters and white space allowed";
        }
    }

    if(empty($_POST["password"])){
        $passwordErr="Password is required";
        $verif=1;
    }else {
        $password = test_input($_POST["password"]);
    }

    if($verif==0 && isset($_POST['user_name']) && isset($_POST['password'])){


        $sql3='SELECT * FROM users where user_name=? and password=? limit 1';
        $stmt=$pdo->prepare($sql3);
        $stmt->execute([$user_name, $password]);
        $users=$stmt->fetchAll();
        
        if(!empty($users)){
        $alreadyExistsErr='User Allreay Exists';
        } else {
        $user_name1=$_POST['user_name'];
        $password1=$_POST['password'];
        $sql = 'INSERT INTO users (user_name,password) VALUES (?,?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([ $user_name1, $password1]);
    
        header("Location: login.php");
        exit;
        }
    }
}

?>

<?php require 'header.php' ?>

<div class="formular">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Register</h1>
        <p>Please fill in this form to create an account.</p>
        <span >*<?php echo $alreadyExistsErr;?></span>
        <hr>
            Name: <input type="text" name="user_name" value="<?php echo $user_name?>"><br>
            <span >*<?php echo $user_nameErr;?></span>
            <br>
            Password: <input type="text" name="password" value="<?php echo $password?>"><br>
            <span >*<?php echo $passwordErr;?></span>
            <br>
            <a href="login.php">LogIn</a>
            <input type="submit" name="register" value="Submit">
            <p>* required field</p>
        </form>
</div>

<?php require_once 'footer.php'; ?>
