<?php

require_once 'common.php';

$username = $password = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $error = [];
    if (empty($_POST['user_name'])) {
        $error['nameErr'] = 'Name is required';
    }
    if (empty($_POST['password'])) {
        $error['passErr'] = 'Pass is required';
    }

    $username = testInput($_POST['user_name']);
    $password = testInput($_POST['password']);

    if (empty($error)) {
        $sql = 'SELECT * FROM users where user_name=? LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user['password'] === $password) {
            $_SESSION['admin'] = $user['admin'];
            $_SESSION['user_id'] = $user['user_id'];
            header('Location: index.php');
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
        *<?= __('Name') ?>: <input type="text" name="user_name" value="<?= $username ?>"><br>
        <?php if (!empty($error['nameErr'])): ?>
            <span>*<?= __($error['nameErr']); ?></span>
        <?php endif; ?>
        <br>
        *<?= __('Password') ?>: <input type="password" name="password" value="<?= $password ?>"><br>
        <?php if (!empty($error['passErr'])): ?>
            <span>*<?= __($error['passErr']); ?></span>
        <?php endif; ?>
        <br>
        <a href="register.php"><?= __('Register') ?></a>
        <input type="submit" name="login" value="<?= __('Submit') ?>">
        <p><?= __('* required field') ?></p>
    </form>
</div>

<?php require_once 'footer.php'; ?>
