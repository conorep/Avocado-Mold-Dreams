<?php
//testing alteration to the session here from the 'add to cart' click on the main page
session_start();
$_SESSION['sessionState'] = "we changed the session 4";
print_r("this page changes session variables, in theory (confirmed reachable by url I'm using with ajax with this print from absolute url)");