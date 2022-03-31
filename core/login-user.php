<?php
session_start();
$connection = require_once 'connection.php';

$login = $_POST['login'];
$password = $_POST['password'];

$login_spec = $connection->real_escape_string($login);
if ($login_spec !== $login) {
    echo 'login-incorrect-slash';
    exit();
}

$password_spec = $connection->real_escape_string($password);
if ($password_spec !== $password) {
    echo 'password-incorrect-slash';
    exit();
}

$query = $connection->query("SELECT `password` FROM `users` WHERE `login` = '$login'");

if (mysqli_num_rows($query) < 1) {
    echo 0;
    exit();
}

$arr = mysqli_fetch_assoc($query);

$query_id_user = $connection->query("SELECT `id` FROM `users` WHERE `login` = '$login'");
$id_user_arr = mysqli_fetch_assoc($query_id_user);

$res = password_verify($password, $arr['password']);
if ($res) {
    $_SESSION['auth'] = 'yes';
    $_SESSION['login'] = $login;
    $_SESSION['id_user'] = $id_user_arr['id'];
    echo 1;
} else {
    echo 0;
}
exit();