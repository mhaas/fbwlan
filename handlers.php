<?php

session_start();

define('FACEBOOK_SDK_V4_SRC_DIR', '/include/facebook-php-sdk-v4/src/Facebook/');
require ('/include/facebook-php-sdk-v4/autoload.php');

require('settings.php');


define('STAGE_LOGIN', 'login');
define('STAGE_COUNTER', 'counters');

define('AUTH_DENIED', '0');
define('AUTH_ALLOWED', '1');
define('AUTH_ERROR', '-1');


use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication(get_setting(KEY_APP_ID),
     get_setting(KEY_APP_SECRET));

function handle_ping(){
    return 'Pong';
}

function handle_fb_callback() {
    $helper = new FacebookRedirectLoginHelper();
    try {
        $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $ex) {
      // When Facebook returns an error
        Flight::error($ex);
    } catch(\Exception $ex) {
        // When validation fails or other local issues
        Flight:error($ex);
    }
    if ($session) {
        $_SESSION['FBTOKEN'] = $session->getToken();
        // Render message form
        Flight::render('', array('post_action' => 'handle_checkin'));
    }
    else {
        // Render error message
    }
    

}

function handle_checkin() {

    $token = $_SESSION['FBTOKEN'];
    if (empty($token)) {
        Flight:error(new Exception('No FB token in session!'));
    }
    $message = Flight::request()->query->message;

    $config = array(place => get_setting(KEY_PLACE_ID));
    if (! empty($message)) {
        array['message'] = $message;
    }

    $request = new FacebookRequest(
        $session,
        'POST',
        '/me/feed'
        $config,
    );

    try {
        $response = $request->execute();
    } catch (FacebookRequestException $ex) {
        Flight::error($ex);
    } catch (\Exception $ex) {
        Flight::error($ex);
    }
}
    

function fblogin() {

    // Simplification: always assume we are not logged in!
    $helper = new FacebookRedirectLoginHelper(MY_URL . 'fb_callback/');
    $loginUrl = $helper->getLoginUrl();
    Flight::render('fblogin', array('url' = > $loginUrl));


}


// User request
function handle_login() {
    //login/?gw_address=%s&gw_port=%d&gw_id=%s&url=%s 
    $gw_address = $request->query->gw_address;
    $gw_port = $request->query->gw_port;
    $gw_id = $request->query->gw_id;
    $req_url = $request->query->url;
    $_SESSION['gw_address'] = $gw_address;
    $_SESSION['gw_port'] = $gw_port;
    $_SESSION['gw_id'] = $gw_id;
    $_SESSION['req_url'] = $req_url;
    fblogin();
}

// Gateway request
function handle_auth() {
    $request = Flight::request();
    // stage=
    //ip=
    //mac=
    //token=
    //incoming=
    //outgoing=
    $gw_address = $request->query->stage;

    if (empty($gw_address) || empty($gw_port) || empty($gw_id)) {
        Flight::Error('stage parameter empty!');
    }
    if ($stage == STAGE_COUNTER) {
        return;
    }
    // TODO: look up in global session? 
}



function write_auth_response($code) {
    echo 'Auth: ' . $code;
}


function login_success() {
    //  http://" . $gw_address . ":" . $gw_port . "/wifidog/auth?token=" . $token
    $token = make_token();
    $_SESSION['gw_address'] = $gw_address;
    $_SESSION['gw_port'] = $gw_port;
    Flight::redirect('http://' . $_SESSION['gw_address'] . ':'
        . $_SESSION['gw_port'] . '/wifidog/auth?token=' . $token);
}


