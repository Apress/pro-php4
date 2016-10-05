SELECT auth_name, ISBN
      FROM author, authortitle
      WHERE author.auth_ID = authortitle.auth_ID;

SELECT auth_name, book_title
      FROM author, authortitle, title
      WHERE author.auth_ID = authortitle.auth_ID
      AND title.ISBN = authortitle.ISBN;



