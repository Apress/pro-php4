connect shop;

CREATE TABLE transaction (
         orderNo INT NOT NULL primary key auto_increment,
         userId VARCHAR(20) NOT NULL,
         itemNo VARCHAR(20) NOT NULL,
         quantity INT NOT NULL DEFAULT 0,
         date date NOT NULL,
         status VARCHAR(20) NOT NULL);
