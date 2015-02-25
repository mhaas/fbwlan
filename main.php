<?php

require 'include/flight/Flight.php';
require 'handlers.php';

Flight::route('/ping', 'handle_ping');

Flight::route('/auth', 'handle_auth');

Flight::route('/login', 'handle_login');

