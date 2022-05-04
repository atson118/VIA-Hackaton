<?php

$name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
$message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

$link = mysqli_connect("localhost", "root", "root", "chat");
$sql = "SELECT * FROM `messages` ORDER BY `date`";
$result = mysqli_query($link, $sql);

if (!isset($_GET['add_message'])) {
    if (mysqli_num_rows($result) >= 1) {
        while($out = mysqli_fetch_assoc($result)) {
            echo $out['name'] . "|" . $out['date'] . "<br>" . $out['message']. "<hr>";
        }
    }
}

if (isset($_GET['add_message'])) {
    echo '<form action="chat.php?add_message" method="POST">
        <input type="text" name="name" style="width:100%; height: 25px;">
        <br><br>
        <input type="text" name="message" style="width:100%; height: 40px;">
        <br><br>
        <button name="submit" style="width:100%; height: 40px;">Отправить</button>
        </form>';
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['name']) && !empty($_POST['message'])) {
        $sql = "INSERT INTO `messages` (`name`, `message`) VALUES('$name', '$message')";
        mysqli_query($link, $sql);
    }
}

?>