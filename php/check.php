<?php

$surname = filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING);
$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$patronymic = filter_var(trim($_POST['patronymic']), FILTER_SANITIZE_STRING);
$date = filter_var(trim($_POST['date']), FILTER_SANITIZE_STRING);
$role = filter_var(trim($_POST['role']), FILTER_SANITIZE_STRING);
$well = filter_var(trim($_POST['well']), FILTER_SANITIZE_STRING);
$photo = filter_var(trim($_POST['photo']), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
$pass = filter_var(trim($_POST['pass']), FILTER_SANITIZE_STRING);

if (mb_strlen($surname) < 3 || mb_strlen($surname) > 100) {
    echo "Недоступная длина";
    exit();
}
else if (mb_strlen($name) < 3 || mb_strlen($name) > 100) {
    echo "Недоступная длина";
    exit();
}
else if (mb_strlen($patronymic) < 3 || mb_strlen($patronymic) > 100) {
    echo "Недоступная длина";
    exit();
}
else if (mb_strlen($well) < 3 || mb_strlen($well) > 100) {
    echo "Недоступная длина";
    exit();
}
else if (mb_strlen($email) < 3 || mb_strlen($email) > 100) {
    echo "Недоступная длина";
    exit();
}
else if (mb_strlen($pass) < 3 || mb_strlen($pass) > 100) {
    echo "Недоступная длина";
    exit();
}

$pass = md5($pass."lebedev");

$mysql = new mysqli ('127.0.0.1','root','root','hackaton');
$mysql->query ("INSERT INTO `users` (`surname`, `name`, `patronymic`, `date`, `role`, `well`, `photo`, `email`, `pass`) VALUES('$surname', '$name', '$patronymic', '$date', '$role', '$well', '$photo', '$email', '$pass')");
$mysql->close();

header('Location: /')

?>