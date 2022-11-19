<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VGKNBot - Admin panel</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/demo.css"/>
    <link rel="stylesheet" href="css/templatemo-style.css">
    <script type="text/javascript" src="js/modernizr.custom.86080.js"></script>
</head>
<body>
<div id="particles-js"></div>
<ul class="cb-slideshow">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>
<div class="container-fluid">
    <div class="row cb-slideshow-text-container ">
        <div class="tm-content col-xl-6 col-sm-8 col-xs-8 ml-auto section">
            <header class="mb-5"><h1>Поделитесь чем-то важным...</h1></header>
            <P class="mb-5">Введите текст сообщения, который будет отправлен всем пользователям, подписанным на
                <a href="https://t.me/VGKNBot">@VGKNBot</a> в Telegram</P>
            <form action="<?php

if (isset ($_POST['text'])) {
    // Переменная с формы
    $text = $_POST['text'];

    // Параметры для подключения
    $db_host = "localhost";
            $db_user = "root"; // Логин БД
            $db_password = "root"; // Пароль БД
            $db_base = 'send_db'; // Имя БД
            $db_table = "messages"; // Имя Таблицы БД

            try {
            // Подключение к базе данных
            $db = new PDO("mysql:host=$db_host;dbname=$db_base", $db_user, $db_password);
            // Устанавливаем корректную кодировку
            $db->exec("set names utf8");
            // Собираем данные для запроса
            $data = array('text' => $text);
            // Подготавливаем SQL-запрос
            $query = $db->prepare("INSERT INTO $db_table (text) values (:text)");
            // Выполняем запрос с данными
            $query->execute($data);
            // Запишем в переменую, что запрос отработал

            $result = true;
            } catch (PDOException $e) {
            // Если есть ошибка соединения или выполнения запроса, выводим её
            print "Ошибка!: " . $e->getMessage() . "<br/>";
            }

            if ($result) {
            echo "Сообщение успешно отправлено";
            }
            }
            ?>" method="POST" class="subscribe-form" >
                <div class="row form-section">
                    <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="text" type="text" class="form-control" id="text"
                               placeholder="Сообщение..." required/>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <button type="send" class="tm-btn-subscribe">Отправить</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <div class="footer-link">
        <p>Copyright © 2022 @VGKNBot - Финальный проект курса Backend-разработка-Kolesa Upgrade
    </div>
</div>
</body>
<script type="text/javascript" src="js/particles.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</html>
