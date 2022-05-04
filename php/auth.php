<?php

$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

$pass = md5($pass."lebedev");

$mysql = new mysqli ('127.0.0.1','root','root','hackaton');

$result = $mysql->query("SELECT * FROM `users` WHERE `email` = '$email' AND `pass` = '$pass'");

$user = $result->fetch_assoc();
if(count($user) == 0) {
    echo "";
    exit();
}

setcookie('user', $user['name'], time() + 3600, "/");

$mysql->close();

header('Location: /')

?>