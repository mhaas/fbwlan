<?php

require_once('include/flight/Flight.php');

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'database');
define('DB_USER', 'user');
define('DB_PASS', 'pass');


Flight::register('db', 'PDO', array('mysql:host='. DB_HOST . ';port=' . DB_PORT .';dbname='. DB_NAME,
    DB_USER, DB_PASS), function($db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});


?>
