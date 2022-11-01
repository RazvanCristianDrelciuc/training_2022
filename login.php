<?php

require_once 'common.php';

$error = ['nameErr' => 'Name is required',
    'passErr' => 'Password is requierd',
    'accErr' => 'THis Account doesnt exist'];

$succes = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $succes = 0;
    if (empty($_POST['user_name']) || empty($_POST['password'])) {
        $succes = 1;
    }
    $user_name = testInput($_POST['user_name']);
    $password = testInput($_POST['password']);

    if ($succes == 0 && isset($user_name) && isset($password)) {
        $sql = 'SELECT * FROM users where user_name=? limit 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_name]);
        $users = $stmt->fetchALL();

        foreach ($users as $user) {
            if ($user['password'] == $password) {
                $_SESSION['admin'] = $user['admin'];
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['logged_in'] = true;
                header('Location: index.php');
            }
        }
    }
}

?>

<?php require_once 'header.php' ?>

<div class="formular">
    <form action="login.php" method="post">
        <h1><?= __('Log in') ?></h1>
        <p><?= __('Please fill in this form to log to an account.') ?></p>
        <hr>
        *<?= __('Name') ?>: <input type="text" name="user_name" value=""><br>
        <?php if (empty($_POST["user_name"])): ?>
            <span>*<?= __($error['nameErr']); ?></span>
        <?php endif; ?>
        <br>
        *<?= __('Password') ?>: <input type="text" name="password" value=""><br>
        <?php if (empty($_POST['password'])): ?>
            <span>*<?= __($error['passErr']); ?></span>
        <?php endif; ?>
        <br>
        <a href="register.php"><?= __('Register') ?></a>
        <input type="submit" name="login" value="<?= __('Submit') ?>">
        <p><?= __('* required field') ?></p>
    </form>
</div>

<?php require_once 'footer.php'; ?>
