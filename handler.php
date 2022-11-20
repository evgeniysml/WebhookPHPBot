<?php

require_once(__DIR__ . '/connect_db.php');
require_once(__DIR__ . '/send_request.php');

$update = json_decode(file_get_contents('php://input'));

file_put_contents(__DIR__ . '/logs.txt', print_r($update, 1), FILE_APPEND);

if (!empty($update)) {
    try {
        $pdo = new PDO($dsn, $user, $pass, $opt);

        $telegram_id = $update->message->chat->id;

        $sql1 = "SELECT COUNT(telegram_id) FROM users WHERE telegram_id = $telegram_id";
        $query1 = $pdo->query($sql1);
        $count = $query1->fetchColumn();

        if ($count == 0) {
            $data = [
                'username' => $update->message->chat->username,
                'telegram_id' => $update->message->chat->id,
                'first_name' => $update->message->chat->first_name,
                'last_name' => $update->message->chat->last_name
            ];
            $sql = "INSERT INTO users (username, telegram_id, first_name, last_name) VALUE
        (:username, :telegram_id, :first_name, :last_name)";

            $query = $pdo->prepare($sql);
            $query->execute($data);
        }

        $sql2 = "SELECT id FROM users WHERE telegram_id = $telegram_id";
        $query2 = $pdo->query($sql2);
        $user_id = $query2->fetchColumn();

        $data3 = [
            'text' => $update->message->text,
            'user_id' => $user_id
        ];

        $sql3 = "INSERT INTO messages (message, user_id) VALUE (:text, :user_id)";
        $query = $pdo->prepare($sql3);
        $query->execute($data3);

    } catch (PDOException $e) {
        print('Error: ' . $e->getMessage());
    }
}

$privet = ["Привет!", "привет!", "привет", "Привет", "/start"];

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