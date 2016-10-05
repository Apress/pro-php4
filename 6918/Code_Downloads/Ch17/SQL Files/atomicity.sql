BEGIN;
  UPDATE Accounts SET balance = 450 WHERE username = 'jon';
  UPDATE Accounts SET balance = 550 WHERE username = 'martin';
COMMIT;
