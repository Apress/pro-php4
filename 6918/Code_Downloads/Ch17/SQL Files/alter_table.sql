ALTER TABLE details ADD COLUMN pages INT (11) UNSIGNED AFTER price;

DESCRIBE details;

ALTER TABLE details DROP COLUMN pages;