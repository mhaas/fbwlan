<?php
/*
 *
 * Copyright 2015 Michael Haas
 *
 * This file is part of FBWLAN.

 * FBWLAN is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation in version 3.
 *
 * FBWLAN is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

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




