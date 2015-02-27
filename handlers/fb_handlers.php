<?php 


// This module handles all interaction with the user's browser
// and Facebook

// TODO: this only works if the script is installed in root
define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/../include/facebook-php-sdk-v4/src/Facebook/');
require_once(__DIR__ . '/../include/facebook-php-sdk-v4/autoload.php');

require_once(__DIR__ . '/../tokens.php');

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

FacebookSession::setDefaultApplication(APP_ID,
     APP_SECRET);


function render_boilerplate() {
    Flight::render('head',
        array(
            'my_url' => MY_URL,
            'title' => _('Log in at ') . PAGE_NAME,
        ),
        'head');
    Flight::render('foot', array(), 'foot');
}


function check_permissions($session) {

    $request = new FacebookRequest(
        $session,
        'GET',
        '/me/permissions'
    );

    try {
        $response = $request->execute();
        $graphObject = $response->getGraphObject()->asArray();
        // http://stackoverflow.com/q/23527919
        foreach ($graphObject as $key => $permissionObject) {
            //print_r($permission);
            if ($permissionObject->permission == 'publish_actions') {
                return $permissionObject->status == 'granted';
            }
        }
    } catch (FacebookRequestException $ex) {
        Flight::error($ex);
    } catch (\Exception $ex) {
        Flight::error($ex);
    }
    return false;
}

// In the FB callback, we show a form to the user
// or an error message if something went wrong.
function handle_fb_callback() {
    render_boilerplate();
    $helper = new FacebookRedirectLoginHelper(MY_URL . 'fb_callback/');
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
                'post_action' => MY_URL .'checkin',
                'suggested_message' => get_suggested_message(),
                'place_name' => PAGE_NAME,
                'retry_url' => Flight::get('retry_url')));
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
        Flight::error(new Exception('Should never get here.'));
    }
}

function handle_checkin() {
    render_boilerplate();
    $token = $_SESSION['FBTOKEN'];
    if (empty($token)) {
        Flight:error(new Exception('No FB token in session!'));
    }
    $session = new FacebookSession($token);
    $message = Flight::request()->query->message;

    $config = array(place => PAGE_ID);
    if (! empty($message)) {
        $config['message'] = $message;
    }
    $request = new FacebookRequest(
        $session,
        'POST',
        '/me/feed',
        $config
    );
    // Some exceptions can be caught and handled sanely,
    // e.g. Duplicate status message (506)
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
    $helper = new FacebookRedirectLoginHelper(MY_URL . 'fb_callback/');
    // We do want to publish to the user's wall!
    $scope = array('publish_actions');
    $fb_login_url = $helper->getLoginUrl($scope);
    $code_login_url = MY_URL . 'access_code/';
    Flight::render('login', array(
        'fburl' => $fb_login_url,
        'codeurl' =>  $code_login_url
        ));

}


function handle_access_code() {

    render_boilerplate();
    $request = Flight::request();
    $code = $request->query->access_code;
    if (empty($code)) {
        Flight::render('denied', array(
            'msg' => _('No access code sent.'),
            'retry_url' => Flight::get('retry_url'),
        ));
    }
    if ($code != ACCESS_CODE) {
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
    // If we get called without the gateway parameters, then we better
    // have these in the session already.
    $gw_address = $request->query->gw_address;
    $gw_port = $request->query->gw_port;
    $gw_id = $request->query->gw_id;
    $req_url = $request->query->url;
    $_SESSION['gw_address'] = $gw_address;
    $_SESSION['gw_port'] = $gw_port;
    $_SESSION['gw_id'] = $gw_id;
    $_SESSION['req_url'] = $req_url;
    if (empty($_SESSION['gw_address']) || empty($_SESSION['gw_port']) || empty($_SESSION['gw_id'])) {
        // reg_url is not that important and might be empty?
        Flight::error(new Exception('Gateway parameters not set in login handler!'));
    }
    Flight::set('retry_url', MY_URL .'login');
    render_boilerplate();
    fblogin();
}


function login_success() {
    //  http://" . $gw_address . ":" . $gw_port . "/wifidog/auth?token=" . $token
    $token = make_token();
    Flight::redirect('http://' . $_SESSION['gw_address'] . ':'
        . $_SESSION['gw_port'] . '/wifidog/auth?token=' . $token);
}

function get_suggested_message() {
    return array_rand(array(SUGGESTED_MESSAGE_1,
        SUGGESTED_MESSAGE_2,
        SUGGESTED_MESSAGE_3));
}
