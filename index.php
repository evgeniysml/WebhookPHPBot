<?php

require_once(__DIR__ . '/connect_db.php');

const TOKEN = '';

const BASE_URL = 'https://api.telegram.org/bot' . TOKEN . '/';

$update = json_decode(file_get_contents('php://input'));

file_put_contents(__DIR__ . '/logs.txt', print_r($update, 1), FILE_APPEND);

try
{
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
}
catch (PDOException $e)
{
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
