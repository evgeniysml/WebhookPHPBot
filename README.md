# WebhookPHPBot

Пока что, он может отвечать на сообщения $privet = ["Привет!", "привет!", "привет", "Привет", "/start"];
приветствием с указанием имени пользователя телеграм.

На все остальные сообщения он отвечает: "Вы написали: {Ваше сообщение}"

Также он записывает всех новых пользователей и их сообщения в базу данных mySQL.

Бот размещен на хостинге и доступен 24/7

Можно его всегда протестировать https://t.me/WebhookPHPBot

На текущий момент нужно доделать:

1. Вывод всех пользователей бота в админке

2. Вывод всех сообщений, который были отправлены всем пользователям.

#Как развернуть бота на своём хостинге.

У вас на хостинге должно быть установленно PHP, MySQL и SSL-сертификат.

1. Зарегистировать бота через https://t.me/BotFather и получить ТОКЕН бота.
2. Выгрузить все файлы в удобную для вас директорию. Например, https://ваш-сайт.com/директория/
3. В файле connect_db.php прописать ваши данные для доступа к базе данных MySQL.
4. В файле send-request.php прописать свой ТОКЕН бота.
5. Выполните запросы из файла query.sql для создания неоходимых таблиц, можно через phpMyAdmin.
6. Уведомите телеграм, что обновления будете получать через webhook'и. 
Для этого нужно ввести в адресную строку браузера 
https://api.telegram.org/bot(ВАШ ТОКЕН без скобочек)/setWebhook?url=https://ваш-сайт.com/директория/handler.php
в ответ в браузере вы должны получить:
{
  ok: true,
  result: true,
  description: "Webhook was set"
}
7. Всё! Бот настроен!
