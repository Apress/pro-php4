SELECT * FROM details
      WHERE price >= 39.95;


ALTER TABLE details
      ADD INDEX price_index (price);
