connect shop;
 
CREATE TABLE Session (
	lastAccessed TIMESTAMP,
	id VARCHAR(255) NOT NULL,
	data TEXT,
	PRIMARY KEY(id)); 

