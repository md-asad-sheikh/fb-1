<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/secret.php';

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

$fb = new Facebook([
    'app_id' => APP_ID,
    'app_secret' => APP_SECRET,
    'default_graph_version' => DEFAULT_GRAPH_VERSION,
]);

$permissions = ['email', 'pages_manage_engagement', 'pages_manage_posts', 'pages_read_engagement', 'pages_read_user_content', 'pages_show_list', 'pages_manage_metadata'];

$helper = $fb->getRedirectLoginHelper();

$login_url = $helper->getLoginUrl(REDIRECT_URL, $permissions);

var_dump($login_url);
die();

// require_once 'Facebook/autoload.php';
// use Facebook\Facebook;
// use Facebook\Exceptions\FacebookResponseException;
// use Facebook\Exceptions\FacebookSDKException;
// $fb = new Facebook(array(
//     'app_id' => FB_APP_ID,
//     'app_secret' => FB_APP_SECRET,
//     'default_graph_version' => META_GRAPH_API_VERSION,
// ));
// $helper = $fb->getRedirectLoginHelper();


try {
    $accessToken = $helper->getAccessToken();
} catch (FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch (FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (!isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());
