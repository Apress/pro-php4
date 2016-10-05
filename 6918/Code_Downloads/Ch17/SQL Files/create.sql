CREATE DATABASE Library;


USE Library;

CREATE TABLE details (
      ISBN VARCHAR (13) NOT NULL,
      price FLOAT,
      num_of_books INT (11) UNSIGNED NOT NULL,
      num_booked INT (11) UNSIGNED NOT NULL,
      series_ID INT (11) NOT NULL,
      PRIMARY KEY (ISBN)
      );


CREATE TABLE title (
      ISBN VARCHAR (13) NOT NULL,
      book_title VARCHAR (255) NOT NULL,
      PRIMARY KEY (ISBN)
      );


CREATE TABLE author (
    auth_ID INT (11) NOT NULL AUTO_INCREMENT,
    auth_name VARCHAR (128) NOT NULL,
    PRIMARY KEY (auth_ID)
   );


CREATE TABLE authortitle (
    ISBN VARCHAR (13) NOT NULL,
    auth_ID INT (11) NOT NULL,
    PRIMARY KEY (ISBN, auth_ID)
   );


CREATE TABLE series (
    series_ID INT (11) NOT NULL AUTO_INCREMENT,
    book_series VARCHAR (64) NOT NULL,
    PRIMARY KEY (series_ID)
   );


CREATE TABLE users (
    username CHAR (32) NOT NULL,
    password CHAR (32) NOT NULL,
    PRIMARY KEY (username)
   );


