<?php
//output buffering
ob_start();

//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('Cart.php');

//testing alteration to the session here from the 'add to cart' click on the main page
session_start();
/*echo("SESSION: <BR>");
var_dump($_SESSION);

echo("<br>POST: <BR>");
var_dump($_POST);
$_SESSION['sessionState'] = "we changed the session 5";
print_r("this page changes session variables, in theory (confirmed reachable by url I'm using with ajax with this print from absolute url)");
//$_SESSION['sessionResult'] = (int)$_POST['val'];*/

$userCart= new Cart();
$userCart->addToCart((int)$_POST['val']);
$_SESSION['sessionCart'] = $userCart;


/*    if(isset($_SESSION['itemIDsForCart'])){
        unset($thisArray);
        $thisArray[] = $_SESSION['itemIDsForCart'];
        $thisArray[] = $_POST['myVar'];
        $_SESSION['itemIDsForCart'] = $thisArray;
    } else {
        $thisArray[] = $_POST['val'];
        $_SESSION['itemIDsForCart'] = $thisArray;
    }*/