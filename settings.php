<?php

require_once('include/flight/Flight.php');


$db = Flight::db();




function init() {

    $db->exec('CREATE TABLE IF NOT EXISTS settings id INT PRIMARY KEY, key VARCHAR(255) NOT NULL UNIQUE, value TEXT');
    
}

function set_setting($key, $value) {

    $db->exec('INSERT INTO settings (key,value) VALUES (foo, bar) ON DUPLICATE KEY UPDATE value=bar');

}

function get_setting($key) {

    // TODO: Prepared statements required!
    $data = $db->query('SELECT value from settings WHERE key=foo');
    if (empty($data)) {
        return null;
    }
    return $data['value'];

}

