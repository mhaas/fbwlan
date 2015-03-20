<?php

// Rename to config.php

// database host
define('DB_HOST', 'localhost');
// database port
define('DB_PORT', '3306');
// database name
define('DB_NAME', 'database');
// database user name
define('DB_USER', 'user');
// database password
define('DB_PASS', 'pass');

// Facebook app id
// See README.md for details
define('APP_ID', 'APP_ID');

// Facebook app secret
define('APP_SECRET', 'APP_SECRET');

// ID of your Facebook page
// This is where people will be checking in
define('PAGE_ID', 'PAGE_ID');

// Name of your facebook page
// This is how your place will be called in this script
// e.g. "Log in at PAGE_NAME"
define('PAGE_NAME', 'PAGE_NAME');

// The URL where this script lives
// This will typically a directory as the .htaccess
// rewrites all requests to index.php
// Example: https://example.xyz/wifi/
define('MY_URL', 'MY_URL');

// How long the wifi session persists before if expires
// Set this in Minutes
define('SESSION_DURATION', 60 * 12);

// This is the access code you can hand out to
// people if they do not want to use Facebook.
// Note: case sensitive!
define('ACCESS_CODE', 'ACCESS_CODE');

// Don't forget to whitelist these domains on the gateway!
// URL pointing to an extended privacy policy
// Note that this script ships with a default privacy notice
// which may not be suitable for you
// If set to the empty string, then the link will not be rendered
define('EXTENDED_PRIVACY_URL', 'http://example.xyz/privacy/');

// URL pointing to an imprint as required in some jurisdictions
// If set to the empty string, then the link will not be rendered
define('IMPRINT_URL', 'http://example.xyz/imprint/');

// How long the session cookie is valid.
// You probably do not have to change this.
// In seconds
define('COOKIE_SESSION_DURATION', 3600 * 24);

// Where user is sent after login is done
define('PORTAL_URL', 'http://example.xzy/portal/');

// Display helpful message to the FB review people
define('FB_REVIEW', False);

// Where you host your copy of the code.
// If you did not modify anything, use the github
// below. Otherwise, the AGPL requires that you provide
// a download link for your modified sources (unless
// you're under some special circumstances, see the license).
define('CODE_URL', 'https://www.github.com/mhaas/fbwlan/')

?>
