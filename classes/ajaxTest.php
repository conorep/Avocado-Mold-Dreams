<?php
//output buffering
ob_start();

//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('cart.php');

//testing alteration to the session here from the 'add to cart' click on the main page
session_start();
$userCart= new Cart();
$userCart->addToCart((int)$_POST['val']);
$_SESSION['sessionCart'] = $userCart;