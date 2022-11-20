CREATE TABLE users (
    id int PRIMARY KEY AUTO_INCREMENT,
    username varchar(255),
    telegram_id INT NOT NULL UNIQUE,
    first_name varchar(255),
    last_name varchar(255)
);

CREATE TABLE messages (
    id int PRIMARY KEY AUTO_INCREMENT,
    message text,
    user_id int,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE sent_of_everyone (
    id int PRIMARY KEY AUTO_INCREMENT,
    message text
);