<?php

require_once('include/flight/Flight.php');

$db = Flight::db();



define('KEY_APP_ID', 'APP_ID');
define('KEY_APP_SECRET', 'APP_SECRET');
define('KEY_PAGE_ID', 'PAGE_ID');
define('KEY_MY_URL', 'MY_URL');
define('KEY_SESSION_DURATION', 'SESSION_DURATION');
define('VALUE_DEFAULT', 'DEFAULT');


$keys = array(KEY_APP_ID, KEY_APP_SECRET, KEY_PAGE_ID);



function init() {

    $db->exec('CREATE TABLE IF NOT EXISTS settings id INT PRIMARY KEY, key VARCHAR(255) NOT NULL UNIQUE, value TEXT');

    // Populate the keys to make it easier for the user to set the correct values
    foreach ($keys as $key) {
        if (empty(get_setting($key))) {
            set_setting($key, VALUE_DEFAULT);
        }

    }
    
}

function set_setting($key, $value) {

    $stmt = $db->prepare('INSERT INTO settings (key,value) VALUES (:key, :value) ON DUPLICATE KEY UPDATE value=:value');
    $stmt->bindParam(':key', $key);
    $stmt->bindParam(':value', $value);
    $stmt->execute();


}

function get_setting($key) {

    // TODO: Prepared statements required!
    $stmt = $db->query('SELECT value from settings WHERE key=:key');
    $stmt->bindParam(':key', $key);

    $data = $stmt->execute();
    if (empty($data)) {
        return null;
    }
    return $data['value'];

}

