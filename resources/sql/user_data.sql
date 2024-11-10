CREATE DATABASE IF NOT EXISTS QuickShop_data;

USE QuickShop_data;

CREATE TABLE IF NOT EXISTS user (
    user_id     INT AUTO_INCREMENT  PRIMARY KEY,
    usn         VARCHAR(255)        NOT NULL,
    pwd         VARCHAR(255)        NOT NULL
);

CREATE TABLE IF NOT EXISTS shopping_list (
    shopping_id         INT AUTO_INCREMENT  PRIMARY KEY,
    user_id             INT                 NOT NULL,
    image_url           VARCHAR(500)        NOT NULL,
    price               FLOAT               NOT NULL,
    name                VARCHAR(500)        NOT NULL,
    URL                 VARCHAR(500)        NOT NULL,
    store               VARCHAR(25)        NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id)
);