<?php
/**
 * This file  is home file for twitter demo.
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
$call = "";
if (isset($_GET['logout']) && !isset($_GET['action'])) {
    $call = "<div class='alert alert-success alert-dismissable fade in'>"
    . "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
            . "<strong>Success!</strong>"
            . "You have been logged out successfully!"
            . "</div>";
}
if (isset($_GET['action']) && !isset($_GET['logout'])) {
    $call = "<div class='alert alert-danger alert-dismissable fade in'>"
    . "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
            . "<strong>Wrong!</strong>"
            . "You need to login first to access!"
            . "</div>";
}


use Abraham\TwitterOAuth\TwitterOAuth;

$url = "";
define('CONSUMER_KEY', 'consumer_key'); 
define('CONSUMER_SECRET', 'consumer_secret_key');
define('OAUTH_CALLBACK', 'callback_url');

if (!isset($_SESSION['access_token'])) {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $request_token=$connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
    $otoken = $request_token['oauth_token'];
    $osecret = $request_token['oauth_token_secret'];
    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $osecret;
    $url = $connection->url('oauth/authorize', array('oauth_token' => $otoken));
} else {
    echo "<script>window.location='twitter_timeline_challange';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Twitter timeline challange | rtCamp</title>

        <!-- Bootstrap Core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="css/style.css" rel="stylesheet">

    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" 
    type="text/css">


    </head>

    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
            <div class="container topnav">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" 
            data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
    <a class="navbar-brand topnav" href="javascript:void(0);">TWIFA</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#about">Developer Info</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>


        <!-- Header -->
        <a name="home"></a>
        <div class="intro-header">
            <?php echo $call; ?>
            <div class="container">

                <div class="row">

                    <div class="col-lg-12">
                        <div class="intro-message">
                            <h1>TWIFA</h1>
                            <h3>rtCamp Assignment</h3>
                            <hr class="intro-divider">
                            <ul class="list-inline intro-social-buttons">
                                <li>
                                    <a href="<?php echo $url; ?>" 
                                       class="btn btn-default btn-lg"> 
                                        <span class="network-name">Sign In</span>
                                        <i class="fa fa-twitter-square fa-fw"></i>
                                    </a>
                                </li>
                                <li>
    <a href="https://github.com/malav-vasita/twitter-demo-for-rtCamp" 
       target="_blank" class="btn btn-default btn-lg">
    <span class="network-name">Github Repo</span>
    <i class="fa fa-github-square fa-fw"></i>
    </a>
                                </li>
                                <li>
    <a href="https://www.linkedin.com/in/malav-vasita-39843190/" 
    target="_blank" class="btn btn-default btn-lg"> 
    <span class="network-name">Developer Info</span>
    <i class="fa fa-linkedin-square fa-fw"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <a  name="services"></a>
        <div class="content-section-a">

        </div>

        <a  name="about"></a>
        <div class="content-section-a">

            <div class="container">

                <div class="row">
                    <div class="col-lg-5 col-sm-6">
                        <div class="clearfix"></div>
    <h2 class="section-heading">MALAV VASITA</h2>
    <p style="margin-top: -25px;">NIRMA UNIVERSITY</p><hr>
                        <p class="lead">He is interested in web development
    and he is having good knowledge of web technologies also. 
    He is creative minded and always try to explore development things 
    to turn them in his own fashion.</p>
                        <ul class="list-inline banner-social-buttons">
                            <li>
    <a href="https://www.facebook.com/malav.vasita.am" target="_blank" 
    class="btn btn-default btn-lg">
    <span class="network-name">Facebook</span>
    <i class="fa fa-facebook-square fa-fw"></i> 
    </a>
                            </li>
                            <li>
    <a href="https://twitter.com/malavvasita" target="_blank"
    class="btn btn-default btn-lg">
        <span class="network-name">Twitter</span>
    <i class="fa fa-twitter-square fa-fw"></i>
    </a>
                            </li>
                            <li>
    <a href="https://www.linkedin.com/in/malav-vasita-39843190/" target="_blank"
       class="btn btn-default btn-lg">
        <span class="network-name">Linkedin</span>
        <i class="fa fa-linkedin-square fa-fw"></i>
    </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                        <img class="img-responsive" src="img/developer.jpg" alt="">
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.content-section-a -->

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
    <p class="copyright text-muted small">
    <a target="_blank" href="https://careers.rtcamp.com/web-engineer/assignments/">
        Twitter timeline challange
    </a> 
    | <a href="https://rtcamp.com/" target="_blank">
        rtCamp Solutions Pvt. Ltd.</a></p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

    </body>

</html>
