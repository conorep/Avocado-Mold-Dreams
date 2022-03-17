<?php
    /*This index page provides the routing calls to site pages.*/
    //output buffering
    ob_start();

    //turn on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    //require the autoload file
    require_once('vendor/autoload.php');

    session_start();

    //comment out when not in use, using this for testing purposes to see what our cart is doing
/*    echo("SESSION: <BR>");
    var_dump($_SESSION);
    echo("<br>POST: <BR>");
    var_dump($_POST);*/

    //create an instance of the Base class for fat free
    $f3 = Base::instance();
    $con = new Controller($f3);
    $dataLayer = new DataLayer();
    $cart = new Cart();

    $f3->route('GET|POST /', function ()
    {
        session_destroy();
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

    $f3->route('GET /logout', function ()
    {
        $GLOBALS['con']->logOut();
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