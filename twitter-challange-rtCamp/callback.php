<?php
/**
 * This file is callback file for twitter demo.
 * 
 * PHP version 5.4
 * 
 * @category PHP
 * @package  Master
 * @author   Authors <malavvasita.mv@gmail.com>
 * @license  MIT Licence
 * @link     https://github.com/malav-vasita/twitter-demo-for-rtCamp
 */
session_start();
require './lib/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_REQUEST['oauth_verifier'], $_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']) {
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
    $_SESSION['access_token'] = $access_token;
    // redirect user back to index page
    header('Location: ./twitter_timeline_challange');
}
?>
