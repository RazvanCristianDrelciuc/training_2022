<?php

require_once 'common.php';

$pdo = pdo_connect_mysql();

$user_name=$password="";
$user_nameErr=$passwordErr=$accountErr="";
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
    
    if($verif == 0 && isset($user_name) && isset($password)){
        $sql='SELECT * FROM users where user_name=? limit 1';
        $stmt=$pdo->prepare($sql);
        $stmt->execute([$user_name]);
        $users=$stmt->fetchALL();
        
        foreach($users as $user) {
            if ($user['password'] == $password) {
                $_SESSION['admin'] = $user['admin'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['logged_in'] = true;
                header('Location: test.php');
            } else {
                $accountErr = 'This Account Doesnt exists';
            }
        }
    }
}

?>

<?php require 'header.php' ?>

<div class="formular">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <h1>Log In</h1>
        <p>Please fill in this form to log to an account.</p>
        <span >*<?php echo $accountErr;?></span>

        <hr>
            Name: <input type="text" name="user_name" value=""><br>
            <span >*<?php echo $user_nameErr;?></span>
            <br>
            Password: <input type="text" name="password" value=""><br>
            <span >*<?php echo $passwordErr;?></span>
            <br>
            <a href="register.php">Register</a>
            <input type="submit" name="login" value="Submit">
            <p>* required field</p>
        </form>
</div>

<?php require_once 'footer.php'; ?>
