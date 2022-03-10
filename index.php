<?php
    //output buffering
    ob_start();

    //turn on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);


    //require the autoload file
    require_once('vendor/autoload.php');

    session_start();
    echo("SESSION: <BR>");
    var_dump($_SESSION);
    echo("<br>POST: <BR>");
    var_dump($_POST);
    //create an instance of the Base class for fat free
    $f3 = Base::instance();
    $con = new Controller($f3);
    $dataLayer = new DataLayer();

    $f3->route('GET|POST /', function ()
    {
        $GLOBALS['con']->home();
    });

    $f3->route('GET|POST /my-account', function ()
    {
        $GLOBALS['con']->accountLogin();
    });

    $f3->route('GET|POST /admin', function ()
    {
        $GLOBALS['con']->admin();
    });

    $f3->route('GET|POST /customer', function ()
    {
        $GLOBALS['con']->customer();
    });

    $f3->route('GET|POST /cart', function ()
    {
        $GLOBALS['con']->cart();
    });

    //run fat-free -> invokes
    $f3->run();

    /*
    Conor, Pat, Regina
    SDEV328 AMD
    index.php
    */

    //send output to the browser
    ob_flush();