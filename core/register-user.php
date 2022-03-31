<?php
session_start();
$connection = require_once 'connection.php';

$login = mysqli_real_escape_string($connection, $_POST['login']);
if ($login !== $_POST['login']) {
    echo 'login-incorrect-slash';
    exit();
}

$login_spec = htmlspecialchars($login);
if ($login_spec !== $login) {
    echo 'login-incorrect-html';
    exit();
}

$select_login = mysqli_query($connection, "SELECT login FROM users WHERE login = '$login_spec'");
if (mysqli_num_rows($select_login) !== 0){
    echo 'login-exist';
    exit();
}

$email = mysqli_real_escape_string($connection, $_POST['email']);
if ($email !== $_POST['email']) {
    echo 'email-incorrect-slash';
    exit();
}

$select_email = mysqli_query($connection, "SELECT login FROM users WHERE email = '$email'");
if (mysqli_num_rows($select_email) !== 0){
    echo 'email-exist';
    exit();
}

if ($_POST['password'] !== $_POST['password_confirm']) {
    echo 'password-incorrect-differ';
    exit();
}

$password = mysqli_real_escape_string($connection, $_POST['password']);
if ($password !== $_POST['password']) {
    echo 'password-incorrect-slash';
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'email-incorrect';
    exit();
}

$password_h = password_hash(mysqli_real_escape_string($connection, $_POST['password']), PASSWORD_BCRYPT);

$file = addslashes(file_get_contents('https://i.ibb.co/hcChbky/user-avatar.png'));
$insert_user = mysqli_query($connection, "INSERT INTO users VALUES (NULL, '$login', '$email', '$password_h', '$file', 1)");

$query_id_user = mysqli_query($connection, "SELECT id FROM users WHERE login = '$login'");
$id_user_arr = mysqli_fetch_assoc($query_id_user);

if (!$insert_user) {
    echo mysqli_error($connection);
    exit();
}

$_SESSION['auth'] = 'yes';
$_SESSION['login'] = $login;
$_SESSION['id_user'] = $id_user_arr['id'];
echo 1;
exit();