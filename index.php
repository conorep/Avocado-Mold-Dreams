<?php
//this is the controller page for AMD

//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//require the autoload file
require_once('vendor/autoload.php');

//create an instance of the Base class for fat free
$f3 = Base::instance();

$f3->route('GET /', function()
{
    $views = new Template();
    echo $views->render('views/home.html');
});

$f3->route('GET|POST /my-account', function()
{
    $views = new Template();
    echo $views->render('views/my-account.html');
});

$f3->route('GET|POST /admin', function()
{
    $views = new Template();
    echo $views->render('views/admin.html');
});

$f3->route('GET|POST /customer', function()
{
    $views = new Template();
    echo $views->render('views/customer.html');
});

$f3->route('GET|POST /cart', function()
{
    $views = new Template();
    echo $views->render('views/shopping-cart.html');
});

//run fat-free -> invokes
$f3->run();

/*
Conor, Pat, Regina
SDEV328 AMD
index.php
*/