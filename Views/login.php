<?php

$error = false;

if (!empty($_POST)) {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $connection=$db->getPDO();
        if (!$connection) {
            die();
        }
        if ($stmt = $connection->prepare("SELECT user_id, username, password FROM user WHERE username=?")) {
            $stmt->bind_param("s", $_POST['username']);
            $stmt->execute();
            $stmt->bind_result($id, $u, $p);
            $stmt->fetch();
            $stmt->close();
            if (password_verify($_POST['password'], $p) === true) {
                session_start();
                $_SESSION['auth'] = $id;
                header('Location:.');
                exit();
            }
            else {
                $error = true;
            }
        }
    }
    else {
        $error = true;
    }
}

?>
<div class="d-flex min-vh-100 justify-content-center align-items-center text-white">
    <form action="" method="post">
        <div class="form-group">
            <label>Username</label>
            <input class="form-control" type="text" name="username" placeholder="Your username">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input class="form-control" type="password" name="password" placeholder="Your password">
        </div>
        <?php if($error): ?>
            <div class="text-warning">Invalid username or password</div>
        <?php endif ?>
        <div class="text-center"><button type="submit" class="btn btn-primary mt-2">Login</button></div>
    </form>
</div>