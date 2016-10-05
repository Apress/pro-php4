mysql> CREATE TABLE user_profile (
         name VARCHAR(40) NOT NULL,
         user_id VARCHAR(20) NOT NULL,
         password VARCHAR(20) NOT NULL,
         address_line1 VARCHAR(40) NOT NULL,
         address_line2 VARCHAR(40) DEFAULT NULL,
         city VARCHAR(20) NOT NULL,
         country VARCHAR(20) NOT NULL,
         pin VARCHAR(20) NOT NULL,
         gender VARCHAR(20) NOT NULL,
         age VARCHAR(20) NOT NULL,
         email_id VARCHAR(20) NOT NULL,
         phone_number VARCHAR(20) NOT NULL,
         card_no VARCHAR(20) NOT NULL,
         expiry_date VARCHAR(20) NOT NULL,
         card_type VARCHAR(20) NOT NULL,
         account_balance float NOT NULL,
         PRIMARY KEY(user_id));

mysql> CREATE TABLE book_shop (
         item_no VARCHAR(20) NOT NULL,
         item_type VARCHAR(20) NOT NULL,
         title VARCHAR(60) NOT NULL,
         author VARCHAR(60) NOT NULL,
         price float NOT NULL,
         PRIMARY KEY(item_no)); 

mysql> CREATE TABLE music_shop (
         item_no VARCHAR(20) NOT NULL,
         item_type VARCHAR(20) NOT NULL,
         title VARCHAR(60) NOT NULL,
         artist VARCHAR(60) NOT NULL,
         price float NOT NULL,
         PRIMARY KEY(item_no)); 

mysql> CREATE TABLE transaction (
         order_no INT NOT NULL primary key auto_increment,
         user_id VARCHAR(20) NOT NULL,
         item_no VARCHAR(20) NOT NULL,
         quantity INT NOT NULL DEFAULT 0,
         date date NOT NULL,
         status VARCHAR(20) NOT NULL);
