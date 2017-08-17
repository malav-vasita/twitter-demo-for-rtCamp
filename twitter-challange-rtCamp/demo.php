<?php
/**
 * This file file will fetch tweets dynamically for twitter demo.
 * 
 * PHP version 5.4
 * 
 * @category PHP
 * @package  Master
 * @author   Authors <malavvasita.mv@gmail.com>
 * @license  MIT Licence
 * @link     https://github.com/malav-vasita/twitter-demo-for-rtCamp
 */
if (!session_id()) {
    session_start();
}
require './lib/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;
if ($_POST['query']) {
    $connection = $_SESSION['connection'];

    $tweetss = $connection->get('statuses/user_timeline', ['exclude_replies' => true, 'screen_name' => $_POST['query'], 'include_rts' => false]);
    $tweets_selected[] = $tweetss;

    foreach ($tweets_selected as $page) {
        foreach ($page as $key) {
            $response = $key->user->name . "," . $key->user->screen_name . "," . $key->text . "<br>";
        }
    }
    echo $response;
}
?>