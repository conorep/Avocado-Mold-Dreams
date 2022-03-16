<?php
//output buffering
ob_start();

//turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once('cart.php');

//testing alteration to the session here from the 'add to cart' click on the main page
session_start();

//showing us what is in post and session from home.html
echo("SESSION: <BR>");
var_dump($_SESSION);
echo("<br>POST: <BR>");
var_dump($_POST);

//session_destroy();

//carts are currently being overwritten for each 'add to cart', need to check if cart isset, if so, do not create a new cart, just add to new one

//if no existing cart

if(isset($_POST['val'])) {

    if($_POST['flag']=="add") {
        $_SESSION['cartMessage'] = "increment triggered" .$_POST['val'];
        $_SESSION['sessionCart']->incrementCartItem((int)$_POST['val']);
    }

    if($_POST['flag']=="sub") {
        //if quanity is already 1
        if($_SESSION['sessionCart']->getVal($_POST['val']) == 1) {
            $_SESSION['cartMessage2'] = "quantity is 1, removal will take place";
            $_SESSION['sessionCart']->removeCartItem((int)$_POST['val']);
        }
        else {
            $_SESSION['cartMessage'] = "decrement triggered" .$_POST['val'];
            $_SESSION['sessionCart']->decrementCartItem((int)$_POST['val']);
        }

    }

    if($_POST['flag']=="remove") {
        $_SESSION['cartMessage'] = "remove triggered" .$_POST['val'];
        $_SESSION['sessionCart']->removeCartItem((int)$_POST['val']);
    }


}
//if there is an increment signal, increment (add) to item quantity
//$_SESSION['sessionCart']->incrementCartItem((int)$_POST['val']);

/*if(isset($_POST['val'])) {
    if(!(isset($_SESSION['sessionCart']))) {
        $userCart= new cart();
        $userCart->incrementCartItem((int)$_POST['val']);
        $_SESSION['sessionCart'] = $userCart;

    }*/
/*//if cart already exists
    elseif (isset($_SESSION['sessionCart'])) {
        $_SESSION['sessionCart']->incrementCartItem((int)$_POST['val']);
    }*/


/*        if(!(isset($_SESSION['sessionCart']))) {
            $userCart= new cart();
            $userCart->incrementCartItem((int)$_POST['val']);
            $_SESSION['sessionCart'] = $userCart;

        }
//if cart already exists
        elseif (isset($_SESSION['sessionCart'])) {
            $_SESSION['sessionCart']->incrementCartItem((int)$_POST['val']);
        }*/




