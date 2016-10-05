<?php

//Library Application Frontend

/Window creation functions
 
function loadMainWindow() 
{
    GLOBAL $windows;
    GLOBAL $widgets;
    GLOBAL $disconnect_id;

    $windows['main'] = &new GtkWindow();
    $windows['main']->set_policy(true, false, true);
    $windows['main']->set_position(GTK_WIN_POS_CENTER);
    $windows['main']->set_title("Online Library Application");
    $disconnect_id = $windows['main']->connect("destroy", "destroy_wnd");

    $widgets['main']['table'] = &new GtkTable(5, 2, false);
    
    $widgets['main']['login_name'] = &new GtkEntry();
    $widgets['main']['login_pass'] = &new GtkEntry();
    $widgets['main']['login_pass']->set_visibility(false);
    $widgets['main']['login_pass']->connect('activate', 'do_login');

    $widgets['main']['label_name'] = &new GtkLabel('Name: ');
    $widgets['main']['label_pass'] = &new GtkLabel('Pass: ');

    $widgets['main']['login_btn'] = &new GtkButton('Log in');

    $widgets['main']['login_btn']->connect('clicked', 'do_login');

    $widgets['main']['table']->attach($widgets['main']['label_name'],
                                      0, 1,
                                      1, 2);

    $widgets['main']['table']->attach($widgets['main']['label_pass'],
                                      0, 1,
                                      3, 4);

    $widgets['main']['table']->attach($widgets['main']['login_name'],
                                      1, 2,
                                      1, 2);

    $widgets['main']['table']->attach($widgets['main']['login_pass'],
                                      1, 2,
                                      3, 4);

    $widgets['main']['table']->attach($widgets['main']['login_btn'],
                                      0, 2,
                                      4, 5);

    $windows['main']->add($widgets['main']['table']);
    $windows['main']->show_all();
}

function loadSearchPage()
{
    GLOBAL $windows;
    GLOBAL $widgets;

    $windows['search'] = &new GtkWindow();
    $windows['search']->set_title("Online Library Application");
    $windows['search']->set_uposition(50, 50);
    $windows['search']->connect("destroy", "destroy_wnd");

    $widgets['search']['table'] = &new GtkTable(8, 8, false);

    $widgets['search']['label_search'] = &new GtkLabel("Search:");
    $widgets['search']['label_series'] = &new GtkLabel("Series:");
    $widgets['search']['label_by'] = &new GtkLabel("Search by:");

    $widgets['search']['search_txt'] = &new GtkEntry();
    $widgets['search']['search_txt']->connect("activate", "perform_search");

    $series_array = array("Beginners", "Professional", "Early Adopters");
    $widgets['search']['search_series'] = &new GtkCombo();
    $widgets['search']['search_series']->set_popdown_strings($series_array);
   
    $by = array("ISBN", "Author Name", "Title");
    $widgets['search']['search_by'] = &new GtkCombo();
    $widgets['search']['search_by']->set_popdown_strings($by);

    $widgets['search']['search_btn'] = &new GtkButton("Search");
    $widgets['search']['search_btn']->connect("clicked", "perform_search");

    $titles = array("Book Title", "Author", "ISBN",
                    "Series", "No Available", "Price");
    $temp_entry = array("Results...", "", "", "", "", "");

    $widgets['search']['result_list'] = &new GtkCList(6, $titles);
    $widgets['search']['result_list']->set_column_width(0, 200);
    $widgets['search']['result_list']->set_column_width(1, 150);
    $widgets['search']['result_list']->set_column_width(2, 100);
    $widgets['search']['result_list']->set_column_width(3, 100);
    $widgets['search']['result_list']->set_column_width(5, 50);

    $widgets['search']['table']->attach($widgets['search']['label_search'],
                                        4, 5,
                                        0, 1);

    $widgets['search']['table']->attach($widgets['search']['label_series'],
                                        4, 5,
                                        1, 2);

    $widgets['search']['table']->attach($widgets['search']['label_by'],
                                        4, 5,
                                        2, 3);

    $widgets['search']['table']->attach($widgets['search']['search_txt'],
                                        5, 8,
                                        0, 1);

    $widgets['search']['table']->attach($widgets['search']['search_series'],
                                        5, 8,
                                        1, 2);

    $widgets['search']['table']->attach($widgets['search']['search_by'],
                                        5, 8,
                                        2, 3);

    $widgets['search']['table']->attach($widgets['search']['search_btn'],
                                        0, 8,
                                        3, 4);

    $widgets['search']['table']->attach($widgets['search']['result_list'],
                                        0, 8,
                                        4, 8);
                                       
    $windows['search']->add($widgets['search']['table']);
    $windows['search']->show_all();
}

//Action functions

function performSearch()
{
    GLOBAL $windows;
    GLOBAL $widgets;

    $search_txt = $widgets['search']['search_txt']->get_text();
    $series_entry = $widgets['search']['search_series']->entry;
    $search_series = $series_entry->get_text();
    $by_entry = $widgets['search']['search_by']->entry;
    $search_by = $by_entry->get_text();

    switch($search_by)
    {
        case "ISBN":
            $search_field = 'title.ISBN = "'.$search_txt.'"';
            break;
        case "Author Name":
            $search_field = 'Author.auth_name LIKE "%'.$search_txt.'%"';
            break;
        case "Title":
        default:
            $search_field = 'title.book_title LIKE "%'.$search_txt.'%"';          
    }

    $sql = 'SELECT  title.book_title, details.ISBN, price, num_of_books, book_series,'.
          ' Author.auth_name FROM details LEFT JOIN title ON title.ISBN '.
          '= details.ISBN LEFT JOIN series ON details.series_ID = '.
          'series.series_ID LEFT JOIN AuthorTitle ON title.ISBN = '.
          'AuthorTitle.ISBN LEFT JOIN Author ON AuthorTitle.auth_ID = '.
          'Author.auth_ID WHERE '. $search_field.
          ' AND series.book_series LIKE "'.$search_series.'"';

    $result = mysql_query($sql) or die(mysql_error());

    if(!mysql_affected_rows())
    {
        $no_results = array("No Results.", "", "", "", "", "");
        $widgets['search']['result_list']->remove(0);
        $widgets['search']['result_list']->prepend($no_results);
    } 
    else
    {
        $widgets['search']['result_list']->clear();
        while($row = mysql_fetch_array($result))
        {
            $insert_array = array($row['book_title'], $row['auth_name'], 
                                  $row['ISBN'], $row['book_series'],
                                  $row['num_of_books'], number_format($row['price'], 2));
                             
            $widgets['search']['result_list']->append($insert_array);
        }
    }
}

function doLogin()
{
    GLOBAL $windows;
    GLOBAL $widgets;
    GLOBAL $conn;
    GLOBAL $disconnect_id;
    
    if(!$conn) // We haven't connected to MySQL yet
    {
        $conn = mysql_connect('localhost', 'nileshp','parmar' );
        mysql_select_db('library');
    }

    $username = $widgets['main']['login_name']->get_text();
    $password = $widgets['main']['login_pass']->get_text();

    $sql = 'SELECT COUNT(*) AS matched FROM users WHERE username="'
           .$username.'" AND password="'.$password.'"';
    $result = mysql_query($sql);

    $array = mysql_fetch_array($result);
    $windows['main']->disconnect($disconnect_id);
    $windows['main']->destroy();

    if($array['matched'])
        load_search_page();
    else
        quit("Authentication failed");

}

//Signal callback and utility functions
 
 
function quit($msg)
{
    printf($msg."\n");
    gtk::main_quit();
}

function destroyWnd()
{
    gtk::main_quit();
}

//Our actual application
 

dl('php_gtk.'.(strstr(PHP_OS, 'WIN')?'dll':'so')) || 
die("Unable to load PHP-GTK module\n");

echo 'test1\n';

loadMainWindow();

gtk::main();
?>

