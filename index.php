<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WebhookPHPBot - Admin panel</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400">
    <!-- Google web font "Open Sans" -->
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
            <header class="mb-5"><h1>Поделись чем-то важным...</h1></header>
            <P class="mb-5">Введи текст сообщения, который будет отправлен всем пользователям, подписанным на
                @WebhookPHPBot в Telegram</P>
            <form action="#" method="get" class="subscribe-form">
                <div class="row form-section">
                    <div class="col-md-7 col-sm-7 col-xs-7">
                        <input name="message" type="text" class="form-control" id="msg" placeholder="Текст сообщения..."
                               required/>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5">
                        <button type="send" class="tm-btn-subscribe">Отправить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer-link">
        <p>Copyright © 2022 @WebhookPHPBot - Финальный проект курса Kolesa Upgrade
    </div>
</div>
</body>
<script type="text/javascript" src="js/particles.js"></script>
<script type="text/javascript" src="js/app.js"></script>
</html>
<?php

require_once(__DIR__ . '/connect_db.php');

const TOKEN = '';

const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';

$update = json_decode(file_get_contents('php://input'));

file_put_contents(__DIR__ . '/logs.txt', print_r($update, 1), FILE_APPEND);

try {
    $pdo = new PDO($dsn, $user, $pass, $opt);

    $data = [
        'username' => $username = $update->message->chat->username,
        'telegram_id' => $update->message->chat->id,
        'first_name' => $update->message->chat->first_name,
        'last_name' => $update->message->chat->last_name
    ];

    $sql = "INSERT INTO users (username, telegram_id, first_name, last_name) VALUE
        (:username, :telegram_id, :first_name, :last_name)";

    $query = $pdo->prepare($sql);
    $query->execute($data);
} catch (PDOException $e) {
    print('Подключение не удалось: ' . $e->getMessage());
}

$telegram_id = $update->message->chat->id;
$username = $update->message->chat->username;
$first_name = $update->message->chat->first_name;
$last_name = $update->message->chat->last_name;
$privet = ["Привет!", "привет!", "привет", "Привет"];

if (in_array($update->message->text, $privet)) {
    send_request('sendMessage', [
        'chat_id' => $update->message->chat->id,
        'text' => "Привет, <b>{$update->message->from->first_name}</b>!",
        'parse_mode' => 'HTML',
    ]);
} else {
    send_request('sendMessage', [
        'chat_id' => $update->message->chat->id,
        'text' => "Вы написали: <i>{$update->message->text}</i>",
        'parse_mode' => 'HTML',
    ]);
}

function send_request($method, $params = [])
{
    $url = BASE_URL . $method;
    if (!empty($params)) {
        $url = BASE_URL . $method . '?' . http_build_query($params);
    }
    return json_decode(file_get_contents($url));
}
?>
