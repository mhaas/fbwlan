<?php

// Configure some security features
// These already have sane defaults in recent PHP versions,
// so consider this as documentation

ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
// This does have a default value listed?
ini_set('session.cookie_httponly', '1');
// HTTPS only!
ini_set('session.cookie_secure', '1');

session_start();



require_once('include/flight/Flight.php');

// Load constants defined in config
require_once('config.php');

// Sets up DB
require_once('db.php');

require_once('handlers/fb_handlers.php');

Flight::route('/login', 'handle_login');
Flight::route('/fb_callback', 'handle_fb_callback');
Flight::route('/checkin', 'handle_checkin');
Flight::route('/access_code', 'handle_access_code');

require_once('handlers/gw_handlers.php');

Flight::route('/ping', 'handle_ping');
Flight::route('/auth', 'handle_auth');



Flight::start();



?>
