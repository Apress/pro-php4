connect shop;
 
CREATE TABLE BookShop (
         itemNo VARCHAR(20) NOT NULL,
         itemType VARCHAR(20) NOT NULL,
         title VARCHAR(60) NOT NULL,
         author VARCHAR(60) NOT NULL,
         price float NOT NULL,
         PRIMARY KEY(itemNo)); 
