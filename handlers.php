<?php

session_start();


require_once('settings.php');


define('FACEBOOK_SDK_V4_SRC_DIR', '/include/facebook-php-sdk-v4/src/Facebook/');
require_once('/include/facebook-php-sdk-v4/autoload.php');

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;


FacebookSession::setDefaultApplication(get_setting(KEY_APP_ID),
     get_setting(KEY_APP_SECRET));

define('STAGE_LOGIN', 'login');
define('STAGE_COUNTER', 'counters');

define('AUTH_DENIED', '0');
define('AUTH_ALLOWED', '1');
define('AUTH_ERROR', '-1');


function handle_ping(){
    return 'Pong';
}


function check_permissions($session) {

    $request = new FacebookRequest(
        $session,
        'GET',
        '/me/permissions'
    );

    try {
        $response = $request->execute();
        // TODO: verify permission
        $graphObject = $response->getGraphObject();
    } catch (FacebookRequestException $ex) {
        Flight::error($ex);
    } catch (\Exception $ex) {
        Flight::error($ex);
    }
}

// In the FB callback, we show a form to the user
// or an error message if something went wrong.
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
        if (check_permissions($session)) {
            Flight::render('fb_callback', array(
                'post_action' => get_setting('MY_URL') .'checkin'),
                'suggested_message' => get_suggested_message(),
                'place_name' => get_setting(KEY_PAGE_NAME),
                'retry_url' => Flight::get('retry_url'));
        } else {
            // 
            Flight::render('denied', array(
                'msg' => _('The ability to post to Facebook on your behalf is required.'),
                'retry_url' => Flight::get('retry_url'),
            ));
            // TODO: handle_login must be changed to handle requests without GET
            // params! 
        }
    }
    else {
        Flight:error(new Exception('Should never get here.'));
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

    // Everything is OK, users gets access and is handed back to Gateway
    login_success();

    // TODO: show a success message?
    // This would probably depend on the gateway
}
    

function fblogin() {

    // Simplification: always assume we are not logged in!
    $helper = new FacebookRedirectLoginHelper(get_setting('MY_URL') . 'fb_callback/');
    // We do want to publish to the user's wall!
    $scope = array('publish_actions');
    $fb_login_url = $helper->getLoginUrl($scope);
    $code_login_url = get_setting('MY_URL') . 'access_code/';
    Flight::render('login', array(
        'fburl' => $fb_login_url,
        'codeurl' =>  $code_login_url
        ));

}


function handle_access_code() {

    $request = Flight::request();
    $code = $request->query->access_code;
    if (empty($code)) {
        Flight::render('denied', array(
            'msg' => _('No access code sent.')
            'retry_url' => Flight::get('retry_url'),
        ));
    }
    if ($code != get_setting(KEY_ACCESS_CODE)) {
        Flight::render('denied', array(
            'msg' => _('Wrong access code.'),
            'retry_url' => Flight::get('retry_url'),
        ));
    } else {
        login_success();
    }
}


// User request
function handle_login() {
    $request = Flight::request();
    //login/?gw_address=%s&gw_port=%d&gw_id=%s&url=%s 
    $gw_address = $request->query->gw_address;
    $gw_port = $request->query->gw_port;
    $gw_id = $request->query->gw_id;
    $req_url = $request->query->url;
    $_SESSION['gw_address'] = $gw_address;
    $_SESSION['gw_port'] = $gw_port;
    $_SESSION['gw_id'] = $gw_id;
    $_SESSION['req_url'] = $req_url;
    Flight::set('retry_url', get_setting('MY_URL') .'login'));
    fblogin();
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

    if (empty($stage || empty($ip) || empty($mac) || empty($token)) {
        //Flight::Error('Required parameters empty!');
        write_auth_response(AUTH_ERROR);
    }
    // Do some housekeeping
    clear_old_tokens();

    if ($stage == STAGE_COUNTER) {
        return;
    }
    if (is_token_valid($token)) {
        write_auth_response(AUTH_ALLOWED);
    }
    write_auth_response(AUTH_DENIED);

}



function write_auth_response($code) {
    echo 'Auth: ' . $code . '\n';
}


function login_success() {
    //  http://" . $gw_address . ":" . $gw_port . "/wifidog/auth?token=" . $token
    $token = make_token();
    $_SESSION['gw_address'] = $gw_address;
    $_SESSION['gw_port'] = $gw_port;
    Flight::redirect('http://' . $_SESSION['gw_address'] . ':'
        . $_SESSION['gw_port'] . '/wifidog/auth?token=' . $token);
}


