<?php

$message = $_POST['message'];
$users = $_GET['users'];

if (!empty($message)) {
    require_once(__DIR__ . '/connect_db.php');
    require_once(__DIR__ . '/send_request.php');

    $pdo = new PDO($dsn, $user, $pass, $opt);

    $sql = "INSERT INTO sent_of_everyone (message) VALUE (:message)";
    $query = $pdo->prepare($sql);
    $query->execute(['message' => $message]);

    $sql1 = "SELECT telegram_id FROM users";
    $query1 = $pdo->query($sql1)->fetchAll(PDO::FETCH_COLUMN);

    foreach ($query1 as $telegram_id) {
        send_request('sendMessage', [
            'chat_id' => $telegram_id,
            'text' => "Рассылка: <i>$message</i>",
            'parse_mode' => 'HTML',
        ]);
    }
}

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>Панель управления ботом @WebhookPHPBot</title>
</head>
<body>
<div class="container">
    <h3>Панель управления ботом <a href="https://t.me/WebhookPHPBot" target="_blank">@WebhookPHPBot</a></h3>
    <form action="index.php" method="post">
        <input type="text" name="message" id="message" placeholder="Введите текст сообщения..." class="form-control">
        <button type="submit" name="sendMessage" class="btn btn-success">Отправить всем</button>
    </form>
    <h6>
        <?php
        if (!empty($message)){
            echo "Сообщение отправлено!";
        }
        ?>
    </h6>
    <a href="index.php?users=yes"><button class="btn btn-success">Список пользователей</button></a>
    <a href="index.php?users=no"><button class="btn btn-success">Список отправленных сообщений</button></a>
    <?php

    if ($users == 'yes') {
        echo 'Вывести пользователей';
    }

    if ($users == 'no') {
        echo 'Вывести сообщения';
    }
    ?>
</div>
</body>
</html>