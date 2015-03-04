<?php

// Rename to config.php

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_NAME', 'database');
define('DB_USER', 'user');
define('DB_PASS', 'pass');

define('APP_ID', 'APP_ID');
define('APP_SECRET', 'APP_SECRET');
define('PAGE_ID', 'PAGE_ID');
define('PAGE_NAME', 'PAGE_NAME');
define('MY_URL', 'MY_URL');
define('SESSION_DURATION', 'SESSION_DURATION');
define('ACCESS_CODE', 'ACCESS_CODE');


// How long the session cookie is valid.
// In seconds
define('COOKIE_SESSION_DURATION', 3600);

// Don't forget to whitelist this domain on the gateway!
define('EXTENDED_PRIVACY_URL', 'http://example.xyz/privacy/');
define('IMPRINT_URL', 'http://example.xyz/imprint/');
?>
