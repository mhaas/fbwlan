<?php

require_once('include/flight/flight/Flight.php');

require_once('config.php');

Flight::register('db', 'PDO', array('mysql:host='. DB_HOST . ';port=' . DB_PORT .';dbname='. DB_NAME,
    DB_USER, DB_PASS), function($db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});


function init_token_db() {
    $db = Flight::db();
    $db->exec('CREATE TABLE IF NOT EXISTS tokens (id INT PRIMARY KEY, token CHAR(40) NOT NULL UNIQUE, date timestamp NOT NULL)');
}

function generate_token() {
    // Taken from wifidog Token.php, but use sha1 instead of md5sum
    return sha1(uniqid(rand(), 1));
}

function make_token() {
    $db = Flight::db();
    $token = generate_token();
    $stmt = $db->prepare('INSERT INTO tokens (token) VALUES (:token)');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}


function clear_token($key, $value) {
    $db = Flight::db();
    $stmt = $db->prepare('DELETE FROM tokens WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}

function clear_old_tokens() {
    
    $db = Flight::db();
    $stmt = $db->prepare('DELETE FROM tokens WHERE date < DATE_SUB(NOW(), INTERVAL :duration MINUTES)');
    $stmt->bindParam(':duration', SESSION_DURATION);
    $stmt->execute();
    // http://stackoverflow.com/a/13009906
}

function is_token_valid($token) {
    $db = Flight::db();
    $stmt = $db->prepare('SELECT token FROM tokens WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $data = $stmt->execute();
    if (empty($data)) {
        return false;
    }
    if ($data['token'] == $token) {
        return true;
    }
    return false;
}

