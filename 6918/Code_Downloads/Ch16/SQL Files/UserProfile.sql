Connect Shop;
 
CREATE TABLE UserProfile (
         fname VARCHAR(32) NOT NULL,
         lname VARCHAR(32) NOT NULL,
         userId VARCHAR(16) NOT NULL,
         password VARCHAR(16) NOT NULL,
         address VARCHAR(128) NOT NULL,
         city VARCHAR(64) NOT NULL,
         country VARCHAR(16) NOT NULL,
         zipCode VARCHAR(8) NOT NULL,
         gender VARCHAR(8) NOT NULL,
         age INTEGER NOT NULL,
         emailId VARCHAR(64) NOT NULL,
         phoneNumber VARCHAR(16) NOT NULL,
         cardNo VARCHAR(16) NOT NULL,
         expiryDate DATE NOT NULL,
         cardType VARCHAR(16) NOT NULL,
         accountBalance float NOT NULL,
         PRIMARY KEY(userId));
