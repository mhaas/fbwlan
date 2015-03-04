<?php

require_once('include/flight/flight/Flight.php');

require_once('config.php');

Flight::register('db', 'PDO', array('mysql:host='. DB_HOST . ';port=' . DB_PORT .';dbname='. DB_NAME,
    DB_USER, DB_PASS), function($db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});


function init_token_db() {
    $db = Flight::db();
    $db->exec('CREATE TABLE IF NOT EXISTS tokens (id INT AUTO_INCREMENT PRIMARY KEY, token CHAR(40) NOT NULL UNIQUE, date timestamp NOT NULL)');
}

function generate_token() {
    // Taken from wifidog Token.php, but use sha1 instead of md5sum
    return sha1(uniqid(rand(), 1));
}

function make_token() {
    // Temporary: purge tokens more often
    // Tokens are cleared on GW communication,
    // but there is no gateway right now
    clear_old_tokens();
    $db = Flight::db();
    $token = generate_token();
    $stmt = $db->prepare('INSERT INTO tokens (token) VALUES (:token)');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    return $token;
}


function clear_token($key, $value) {
    $db = Flight::db();
    $stmt = $db->prepare('DELETE FROM tokens WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}

function clear_old_tokens() {
    
    $db = Flight::db();
    //$stmt = $db->prepare('DELETE FROM tokens WHERE date < DATE_SUB(NOW(), INTERVAL :duration MINUTES)');
    // Cannot pass a constant by reference in $stmt->bindParam
    // As there is no security problem, simply concatenate SESSION_DURATION into string
    // http://stackoverflow.com/questions/6130077
    //$stmt->bindParam(':duration', SESSION_DURATION);
    $stmt = $db->prepare('DELETE FROM tokens WHERE date < DATE_SUB(NOW(), INTERVAL ' . SESSION_DURATION . ' MINUTE)');
    $stmt->execute();
    // http://stackoverflow.com/a/13009906
}

function is_token_valid($token) {
    $db = Flight::db();
    $stmt = $db->prepare('SELECT token FROM tokens WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (empty($data)) {
        return false;
    }
    if ($data['token'] == $token) {
        return true;
    }
    return false;
}

