<?php
/**
 * This file  is main file for twitter demo.
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
ini_set('display_errors', 'Off');
if (!isset($_SESSION['access_token'])) {
    echo "<script>window.location='index?action';</script>";
}
require './lib/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

$url = "";
define('CONSUMER_KEY', 'nhQ1zywfoWnVwrllo4ZcKEyYO');
define('CONSUMER_SECRET', 'bnTCZ86yVaeYxVdjOl5zhSw150a2jYML0KJdjSPcrxQLrxd7Zl');
define('OAUTH_CALLBACK', 'http://localhost/rtCamp_theme/callback.php');

$access_token = $_SESSION['access_token'];
$os = $access_token['oauth_token_secret'];
$ot = $access_token['oauth_token'];
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $ot, $os);
$user = $connection->get("account/verify_credentials");
$list = $connection->get('followers/list', ['screen_name' => $user->screen_name]);

$_SESSION['connection'] = $connection;
$_SESSION['name'] = $user->name;
$_SESSION['screen_name'] = $user->screen_name;

$all_followers = $connection->get('followers/list', ['screen_name' => $user->screen_name]);
$array_of_followers = array();
for ($i = 0; $i < count($all_followers->users); $i++) {
    $sna = $all_followers->users[$i]->screen_name;
    $array_of_followers[$sna] = $all_followers->users[$i]->name;
}
//print_r($array_of_followers);
$users = $connection->get('users/search', ['q' => 'devik vekariya']);
$tweets = $connection->get('statuses/home_timeline', ['count' => 10, 'exclude_replies' => true, 'screen_name' => $user->screen_name, 'include_rts' => false]);
$user_tweets = $connection->get('statuses/user_timeline', ['count' => 200, 'exclude_replies' => true, 'screen_name' => $user->screen_name, 'include_rts' => false]);

$totalTweets[] = $tweets;
$tweets_downloads[] = $user_tweets;
$img = $user->profile_image_url_https;
$followers[] = $list;

$json_download = "";

if (!file_exists('upload/' . $user->screen_name . 'json')) {
    $fp = fopen('upload/' . $user->screen_name . '.json', 'w');
    fwrite($fp, json_encode($tweets_downloads));
    $json_download = "<a href='upload/'.$user->screen_name.'.json'>"
            . "Download JSON file</a>";
    $_SESSION['json'] = 'upload/' . $user->screen_name . '.json';
    fclose($fp);
} else {
    $_SESSION['json'] = 'upload/' . $user->screen_name . '.json';
    $json_download = "<a href='upload/'.$user->screen_name.'.json'>Download JSON file</a>";
}


if (!file_exists('upload/' . $user->screen_name . 'csv')) {
    $f = fopen('upload/' . $user->screen_name . '.csv', 'w');
    $_SESSION['csv'] = 'upload/' . $user->screen_name . '.csv';
    foreach ($tweets_downloads as $page) {
        foreach ($page as $line) {
            $csvdata = $line->user->name . "," . $line->text . "\n";
            fwrite($f, $csvdata);
        }
    }
    fclose($f);
} else {
    $_SESSION['csv'] = 'upload/' . $user->screen_name . '.csv';
    $json_download = "<a href='upload/'.$user->screen_name.'.csv'>Download CSV file</a>";
}

if ($_POST['query']) {
    $connection = $_SESSION['connection'];

    $tweetss = $connection->get('statuses/user_timeline', ['count' => 10, 'exclude_replies' => true, 'screen_name' => $_POST['query'], 'include_rts' => false]);
    $tweets_selected[] = $tweetss;
    $response = "[";
    foreach ($tweets_selected as $page) {
        foreach ($page as $key) {
            $response .= "\"" . $key->user->profile_background_image_url_https . "|" . $key->user->name . "|" . $key->user->screen_name . "|" . $key->text . "\",";
        }
    }
    $response = substr($response, 0, -1);
    $response .= "]";
    echo $response;
    exit();
}

$start = 1;
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
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
        <!-- Custom Fonts -->
        <link href="font-awesome/css/font-awesome.min.css" 
              rel="stylesheet" type="text/css">
        <script>

            $(document).ready(function () {
                $('#example').DataTable({
                    "searching": false,
                    "paging": true,
                    "info": false,
                    "lengthChange": false
                });
            });
            function call(obj) {
                $('#example').DataTable();
                var t = $(obj).text();
                var id = t.split('@');
                $("#tweets_slider").html("");
                $("#tweets_slider").html("<img src='img/twitter_logo_gif.gif' style='height:20%; width:20%; margin-top: 5%;'>").addClass("img_loading");
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    data: "&query=" + id[1].trim(),
                    success: function (data) {
                        $("#tweets_slider").removeClass().html();
                        $("#tweets_slider").addClass('carousel-inner');
                        var length = data.length;
                        for (var i = 0; i < length; i++) {

                            var data_parts = data[i].split('|');
                            if (i != 0) {
                                $("#tweets_slider").append("<div class='item'><div class='row'><div class='col-sm-12 col-md-12'><div class='thumbnail' style='text-align: center;'><img src='" + data_parts[0] + "' onerror='this.src=&quot;img/twitter_logo.png&quot;;' style='display:inline; width:65px; height:65px; border-radius: 100%;'><h3>" + data_parts[1] + "</h3><br><h4>@" + data_parts[2] + "</h4><p><br>" + data_parts[3] + "<br></p></div></div></div></div>").addClass('slider' + i);
                            } else {
                                $("#tweets_slider").html("<div class='item active'><div class='row'><div class='col-sm-12 col-md-12'><div class='thumbnail' style='text-align: center;'><img src='" + data_parts[0] + "' onerror='this.src=&quot;img/twitter_logo.png&quot;;' style='display:inline; width:65px; height:65px; border-radius: 100%;'><h3>" + data_parts[1] + "</h3><br><h4>@" + data_parts[2] + "</h4><p><br>" + data_parts[3] + "<br></p></div></div></div></div>").addClass('slider' + i);
                            }
                        }

                    }
                });
            }
        </script>
    </head>

    <body>
        <div id="pageid"></div>
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
            <div class="container topnav">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" 
                            data-toggle="collapse" 
                            data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand topnav" 
                       href="javascript:void(0);">TWIFA</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" 
                     id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="logout">LOGOUT</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </div>
            <!-- /.container -->
        </nav>


        <!-- Header -->
        <a name="home"></a>
        <div class="content-section-a">
            <div class="container">
                <div class="col-md-12">
                    <div id="carousel-example-generic" 
                         class="carousel slide" data-ride="carousel">

                        <div class="carousel-inner" style="text-align: center" id="tweets_slider">
                            <?php
                            foreach ($totalTweets as $page) {
                                foreach ($page as $key) {
                                    $a = ($start == 1) ? 'active' : '';
                                    echo "<div class='item $a'>
                                <div class='row'>
                                    <div class='col-sm-12 col-md-12'>
    <div class='thumbnail' style='text-align: center;'>
    <img src='{$key->user->profile_background_image_url_https}' 
    style='display:inline; width:65px; height:65px; border-radius: 100%;'>
                                            <h3>" . $key->user->name . "</h3>
                                        <br><h4>@" . $key->user->screen_name . "</h4>
                                            <p><br>" . $key->text . '<br>' . "</p>
                                        </div>
                                    </div>
                                </div>
                            </div>";
                                    $start++;
                                }
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="content-section-a">

            <div class="container">

                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="clearfix"></div>
                        <h4 class="section-heading">Download your tweets</h4>
                        <span class="download">
                            <i class="fa fa-download space">&nbsp;
                                <a href="
                                    <?php echo (file_exists('upload/' . $user->screen_name . '.csv')) ? 'upload/' . $user->screen_name . '.csv' : 'javascript:void(0);' ?>">
                                    CSV</a>&nbsp;&nbsp;</i>
                            <i class="fa fa-download space">&nbsp;
                                <a 
    href="<?php echo (file_exists('upload/'.$user->screen_name.'.json'))?'upload/'.$user->screen_name.'.json':'javascript:void(0);'; ?>" 
                                    target="_blank">JSON</a>
                            </i>
                    </div>
                </div>

            </div>
            <!-- /.container -->

        </div>
        <!-- /.intro-header -->

        <!-- /.content-section-a -->

        <a  name="about"></a>
        <div class="content-section-b">

            <div class="container">

                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <hr class="section-heading-spacer">
                        <div class="clearfix"></div>
                        <h2 class="section-heading">Followers</h2><hr>
                        <input type="text" name="follower" 
    onkeyup="myFunction()" id="follower" class="input-text" 
                               placeholder="Search Follower" />  
    <table id="example" 
           class="table table-hover table-bordered dt-responsive nowrap" 
                               cellspacing="0" width="100%">
                            <thead>
                                <tr><td>Your followers list</td></tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($followers as $page) {
                                    if (isset($page)) {
                                        foreach ($page as $line) {
                                            if (isset($line)) {
                                                foreach ($line as $key) {
                                                    if (isset($key)) {
                                                        $sname = $key->screen_name;
                                                        echo "<tr>
                                                              <td>
   <img src='{$key->profile_image_url_https}' 
       style='border-radius: 100%; height: 30px; width: 30px;'>
    <a class='reflect' onclick='call(this)'
    style='text-decoration: none; 
    margin-left: 10px;'>{$key->name} | @{$key->screen_name}
        </a>
                                                              </td>
                                                             </tr>";
                                                        $start++;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                ?>
                            <script>
                                function myFunction() {
                                    var input, filter, table, tr, td, i;
                                    input = document.getElementById("follower");
                                    filter = input.value.toUpperCase();
                                    table = document.getElementById("example");
                                    tr = table.getElementsByTagName("tr");
                                    for (i = 0; i < tr.length; i++) {
                                        td = tr[i].getElementsByTagName("td")[0];
                                        if (td) {
    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                                tr[i].style.display = "";
                                            } else {
                                                tr[i].style.display = "none";
                                            }
                                        }
                                    }
                                }



                            </script>
                            </tbody>
                        </table>
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
                            <a target="_blank" 
    href="https://careers.rtcamp.com/web-engineer/assignments/">
                                Twitter timeline challange</a> 
                            | <a href="https://rtcamp.com/" target="_blank">
                                rtCamp Solutions Pvt. Ltd.</a></p>
                    </div>
                </div>
            </div>
        </footer>


    </body>

</html>
