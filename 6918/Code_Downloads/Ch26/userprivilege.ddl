# userprivilege.ddl

CREATE DATABASE IF NOT EXISTS UserPrivilege;

USE UserPrivilege;

CREATE TABLE User (
            username VARCHAR (10) NOT NULL PRIMARY KEY,
            fullname VARCHAR (50)
);

CREATE TABLE Privilege (
            priv_id INT (11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            description VARCHAR (50)
);

CREATE TABLE UserPrivilege (
            username VARCHAR (10) NOT NULL,
            priv_id INT (11) UNSIGNED NOT NULL,
            PRIMARY KEY (username, priv_id)
);
