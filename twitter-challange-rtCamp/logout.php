<?php
/**
 * This file  is logout file for twitter demo.
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
if(file_exists($_SESSION['json'])){
    if(unlink($_SESSION['json'])){
        echo json_decode($_SESSION['json']);
        echo "<script>alert('Files has been removed');</script>";
    }
}
if(file_exists($_SESSION['csv'])){
    if(unlink($_SESSION['csv'])){
        echo json_decode($_SESSION['csv']);
        echo "<script>alert('Files has been removed');</script>";
    }
}
setcookie ("selected", "", time() - 3600);
session_destroy();
echo "<script>window.location='index?logout';</script>";
