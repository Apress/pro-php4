connect shop;

CREATE TABLE musicShop (
         itemNo VARCHAR(20) NOT NULL,
         itemType VARCHAR(20) NOT NULL,
         title VARCHAR(60) NOT NULL,
         artist VARCHAR(60) NOT NULL,
         price float NOT NULL,
         PRIMARY KEY(itemNo)); 
