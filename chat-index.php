<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIA</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>

<div class="preloader">
    <svg class="preloader__image" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <path fill="currentColor"
            d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z">
        </path>
    </svg>
</div>

<?php
    if($_COOKIE['user'] == ''):
?>

<div class="container">

    <header class="header">
        <div class="logo_box">
            <img src="img/logo.png" alt="" class="logo">
            <a href="index.php" class="logo_text">VIA</a>
        </div>
        <div class="search_box">
            <img src="img/Search.png" alt="" class="search_ico">
            <input type="text" class="search" placeholder="Поиск">
        </div>
        <a href="auth.php" class="auth">Вход/Авторизация</a>
    </header>

	<div class="chat_main_box">
    <main class="chat_box">
		<div class="text-center">
			<h2 class="chat_text">Чат программа</h2>
			<p class="lead">Укажите ваше имя и начинайте переписку</p>
		</div>
		<div class="row">
			<div class="col-6">
				<h3 class="chat_form_text">Форма сообщений</h3>
				<form id="messForm" class="chat_form">
					<label for="name" class="chat_form_label">Имя</label> <br>
					<input type="text" name="name" id="name" placeholder="Введите имя" class="form-control">
					<br>
					<label for="message" class="chat_form_label">Сообщение</label> <br>
					<textarea name="message" id="message" class="form-control" placeholder="Введите сообщение"></textarea>
					<br>
					<input type="submit" value="Отправить" class="btn btn-danger">
				</form>
			</div>
			<div class="col-6">
				<h3 class="chat_form_text_1">Сообщения</h3>
				<div id="all_mess"></div>
			</div>
		</div>
	</div>
	<!-- Подключаем jQuery, а также Socket.io -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="/socket.io/socket.io.js"></script>
	<script>
		const socket = io();
	</script>
	<script>
		// У каждого пользователя будет случайный стиль для блока с сообщенями,
		// поэтому в этом кусочке кода мы получаем случайные числа
		var min = 1;
		var max = 6;
		var random = Math.floor(Math.random() * (max - min)) + min;

		// Устаналиваем класс в переменную в зависимости от случайного числа
		// Эти классы взяты из Bootstrap стилей
		var alertClass;
		switch (random) {
			case 1:
				alertClass = 'secondary';
				break;
			case 2:
				alertClass = 'danger';
				break;
			case 3:
				alertClass = 'success';
				break;
			case 4:
				alertClass = 'warning';
				break;
			case 5:
				alertClass = 'info';
				break;
			case 6:
				alertClass = 'light';
				break;
		}

		// Функция для работы с данными на сайте
		$(function() {
			// Включаем socket.io и отслеживаем все подключения
			var socket = io.connect();
			// Делаем переменные на:
			var $form = $("#messForm"); // Форму сообщений
			var $name = $("#name"); // Поле с именем
			var $textarea = $("#message"); // Текстовое поле
			var $all_messages = $("#all_mess"); // Блок с сообщениями

			// Отслеживаем нажатие на кнопку в форме сообщений
			$form.submit(function(event) {
				// Предотвращаем классическое поведение формы
				event.preventDefault();
				// В сокет отсылаем новое событие 'send mess',
				// в событие передаем различные параметры и данные
				socket.emit('send mess', {mess: $textarea.val(), name: $name.val(), className: alertClass});
				// Очищаем поле с сообщением
				$textarea.val('');
			});

			// Здесь отслеживаем событие 'add mess', 
			// которое должно приходить из сокета в случае добавления нового сообщения
			socket.on('add mess', function(data) {
				// Встраиваем полученное сообщение в блок с сообщениями
				// У блока с сообщением будет тот класс, который соответвует пользователю что его отправил
				$all_messages.append("<div class='alert alert-" + data.className + "'><b>" + data.name + "</b>: " + data.mess + "</div>");
			});

		});
	</script>
    </main>   
</div>

<footer class="footer">
    <div class="container">
    <div class="footer_box">
	<div class="footer_nav_box">
        <div class="nav_past">
            <a href="index.php" class="nav">Главное</a>
            <a href="guid.php" class="nav">Гайды</a>
        </div>
        <div class="nav_past">
            <a href="vacancies.php" class="nav">Вакансии</a>
            <a href="map.php" class="nav">Карты</a>
        </div>
        <div class="nav_past">
            <a href="chat-index.php" class="nav">Чат</a>
            <a href="index.php" class="nav">Новости</a>
        </div>
    </div>
        <div class="footer_form_box">
            <form action="php/ajax.php" method="POST" class="footer_message_box">
                <p class="footer_text">Обратная связь</p>
                <input type="text" placeholder="E-mail..." name="email" class="footer_input"><br>
                <input type="text" placeholder="Имя..." name="name" class="footer_input"><br>
                <input type="text" placeholder="Обращение..." name="text" class="footer_input"><br>
                <button class="footer_btn" type="submit">Отправить</button>
            </form>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="js/main.js"></script>
            <div class="icon_box_add">
                <img src="img/VK.png" alt="" class="icon_add">
                <img src="img/Whatsapp.png" alt="" class="icon_add">
                <img src="img/Telegram.png" alt="" class="icon_add">
            </div>
        </div>
        <div class="footer_adress">
            <div class="footer_map_box">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1141.865026208496!2d129.7214865774865!3d62.02075566412808!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5bf64a64c6ea3bcb%3A0xd011544511f6a890!2z0YPQuy4g0JrRgNGD0L_RgdC60L7QuSwgMTMsINCv0LrRg9GC0YHQuiwg0KDQtdGB0L8uINCh0LDRhdCwICjQr9C60YPRgtC40Y8pLCA2NzcwMDc!5e1!3m2!1sru!2sru!4v1651494555478!5m2!1sru!2sru" width="458" height="411" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <p class="footer_adress_text">ул. Крупской, 13, Якутск, Респ. Саха (Якутия), 677007</p>
        </div>
    </div>
    <div class="footer_line_box"><hr class="footer_line"></div>
    <p class="editor">Разработано командой-Мстители </p>
    </div> 
</footer>
<?php
else:
?>
<div class="container">

<header class="header">
	<div class="logo_box">
		<img src="img/logo.png" alt="" class="logo">
		<a href="index.php" class="logo_text">VIA</a>
	</div>
	<div class="search_box">
		<img src="img/Search.png" alt="" class="search_ico">
		<input type="text" class="search" placeholder="Поиск">
	</div>
	<div class="profil_box">
        <p class="auth"><?=$_COOKIE['user']?></p>
        <a href="php/exit.php"><img src="img/profil.png" alt=""></a>
    </div>
</header>

<div class="chat_main_box">
<main class="chat_box">
	<div class="text-center">
		<h2 class="chat_text">Чат программа</h2>
		<p class="lead">Укажите ваше имя и начинайте переписку</p>
	</div>
	<div class="row">
		<div class="col-6">
			<h3 class="chat_form_text">Форма сообщений</h3>
			<form id="messForm" class="chat_form">
				<label for="name" class="chat_form_label">Имя</label> <br>
				<input type="text" name="name" id="name" placeholder="Введите имя" class="form-control">
				<br>
				<label for="message" class="chat_form_label">Сообщение</label> <br>
				<textarea name="message" id="message" class="form-control" placeholder="Введите сообщение"></textarea>
				<br>
				<input type="submit" value="Отправить" class="btn btn-danger">
			</form>
		</div>
		<div class="col-6">
			<h3 class="chat_form_text_1">Сообщения</h3>
			<div id="all_mess"></div>
		</div>
	</div>
</div>
<!-- Подключаем jQuery, а также Socket.io -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="/socket.io/socket.io.js"></script>
<script>
	const socket = io();
</script>
<script>
	// У каждого пользователя будет случайный стиль для блока с сообщенями,
	// поэтому в этом кусочке кода мы получаем случайные числа
	var min = 1;
	var max = 6;
	var random = Math.floor(Math.random() * (max - min)) + min;

	// Устаналиваем класс в переменную в зависимости от случайного числа
	// Эти классы взяты из Bootstrap стилей
	var alertClass;
	switch (random) {
		case 1:
			alertClass = 'secondary';
			break;
		case 2:
			alertClass = 'danger';
			break;
		case 3:
			alertClass = 'success';
			break;
		case 4:
			alertClass = 'warning';
			break;
		case 5:
			alertClass = 'info';
			break;
		case 6:
			alertClass = 'light';
			break;
	}

	// Функция для работы с данными на сайте
	$(function() {
		// Включаем socket.io и отслеживаем все подключения
		var socket = io.connect();
		// Делаем переменные на:
		var $form = $("#messForm"); // Форму сообщений
		var $name = $("#name"); // Поле с именем
		var $textarea = $("#message"); // Текстовое поле
		var $all_messages = $("#all_mess"); // Блок с сообщениями

		// Отслеживаем нажатие на кнопку в форме сообщений
		$form.submit(function(event) {
			// Предотвращаем классическое поведение формы
			event.preventDefault();
			// В сокет отсылаем новое событие 'send mess',
			// в событие передаем различные параметры и данные
			socket.emit('send mess', {mess: $textarea.val(), name: $name.val(), className: alertClass});
			// Очищаем поле с сообщением
			$textarea.val('');
		});

		// Здесь отслеживаем событие 'add mess', 
		// которое должно приходить из сокета в случае добавления нового сообщения
		socket.on('add mess', function(data) {
			// Встраиваем полученное сообщение в блок с сообщениями
			// У блока с сообщением будет тот класс, который соответвует пользователю что его отправил
			$all_messages.append("<div class='alert alert-" + data.className + "'><b>" + data.name + "</b>: " + data.mess + "</div>");
		});

	});
</script>
</main>   
</div>

<footer class="footer">
<div class="container">
<div class="footer_box">
<div class="footer_nav_box">
	<div class="nav_past">
		<a href="index.php" class="nav">Главное</a>
		<a href="guid.php" class="nav">Гайды</a>
	</div>
	<div class="nav_past">
		<a href="vacancies.php" class="nav">Вакансии</a>
		<a href="map.php" class="nav">Карты</a>
	</div>
	<div class="nav_past">
		<a href="chat-index.php" class="nav">Чат</a>
		<a href="index.php" class="nav">Новости</a>
	</div>
</div>
	<div class="footer_form_box">
		<form action="php/ajax.php" method="POST" class="footer_message_box">
			<p class="footer_text">Обратная связь</p>
			<input type="text" placeholder="E-mail..." name="email" class="footer_input"><br>
			<input type="text" placeholder="Имя..." name="name" class="footer_input"><br>
			<input type="text" placeholder="Обращение..." name="text" class="footer_input"><br>
			<button class="footer_btn" type="submit">Отправить</button>
		</form>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="js/main.js"></script>
		<div class="icon_box_add">
			<img src="img/VK.png" alt="" class="icon_add">
			<img src="img/Whatsapp.png" alt="" class="icon_add">
			<img src="img/Telegram.png" alt="" class="icon_add">
		</div>
	</div>
	<div class="footer_adress">
		<div class="footer_map_box">
			<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1141.865026208496!2d129.7214865774865!3d62.02075566412808!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x5bf64a64c6ea3bcb%3A0xd011544511f6a890!2z0YPQuy4g0JrRgNGD0L_RgdC60L7QuSwgMTMsINCv0LrRg9GC0YHQuiwg0KDQtdGB0L8uINCh0LDRhdCwICjQr9C60YPRgtC40Y8pLCA2NzcwMDc!5e1!3m2!1sru!2sru!4v1651494555478!5m2!1sru!2sru" width="458" height="411" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
		<p class="footer_adress_text">ул. Крупской, 13, Якутск, Респ. Саха (Якутия), 677007</p>
	</div>
</div>
<div class="footer_line_box"><hr class="footer_line"></div>
<p class="editor">Разработано командой-Мстители </p>
</div> 
</footer>
<?php
endif;
?>
</body>
<script>
    window.onload = function () {
        document.body.classList.add('loaded_hiding');
        window.setTimeout(function () {
        document.body.classList.add('loaded');
        document.body.classList.remove('loaded_hiding');
        }, 500);
    }
</script>

</html>