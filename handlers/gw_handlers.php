<?php

// This module handles all communication between script and gateway

define('STAGE_LOGIN', 'login');
define('STAGE_COUNTER', 'counters');

define('AUTH_DENIED', '0');
define('AUTH_ALLOWED', '1');
define('AUTH_ERROR', '-1');


function handle_ping(){
    /*return 'Pong';*/
    echo 'Pong\n';
}


// Gateway request
function handle_auth() {
    $request = Flight::request();
    //incoming=
    //outgoing=
    $stage = $request->query->stage;
    $ip = $request->query->ip;
    $mac = $request->query->mac;
    $token = $request->query->token;

    if (empty($stage) || empty($ip) || empty($mac) || empty($token)) {
        //Flight::Error('Required parameters empty!');
        write_auth_response(AUTH_ERROR);
    }
    // Do some housekeeping
    clear_old_tokens();

    // Even on STAGE_COUNTER, check token
    //if ($stage == STAGE_COUNTER) {
    //    return;
    //}
    if (is_token_valid($token)) {
        write_auth_response(AUTH_ALLOWED);
        return;
    }
    write_auth_response(AUTH_DENIED);

}



function write_auth_response($code) {
    echo 'Auth: ' . $code;
}




