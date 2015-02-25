<?php

require_once('include/flight/Flight.php');

require_once('settings.php');

$db = Flight::db();

function init() {
    $db->exec('CREATE TABLE IF NOT EXISTS tokens id INT PRIMARY KEY, token CHAR(40) NOT NULL UNIQUE, date timestamp NOT NULL');
}

function generate_token() {
    // Taken from wifidog Token.php, but use sha1 instead of md5sum
    return sha1(uniqid(rand(), 1));
}

function make_token() {
    $token = generate_token();
    $stmt = $db->prepare('INSERT INTO tokens (token) VALUES (:token)');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}


function clear_token($key, $value) {
    $stmt = $db->prepare('DELETE FROM tokens WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}

function clear_old_tokens() {
    
    $stmt = $db->prepare('DELETE FROM tokens WHERE date < DATE_SUB(NOW(), INTERVAL :duration MINUTES)');
    $stmt->bindParam(':duration', get_setting(KEY_SESSION_DURATION));
    $stmt->execute();
    // http://stackoverflow.com/a/13009906
}

function is_token_valid($token) {
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

