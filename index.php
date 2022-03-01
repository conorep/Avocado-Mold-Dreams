<?php
    //output buffering
    ob_start();

    //turn on error reporting
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    //require the autoload file
    require_once('vendor/autoload.php');
    require('model/validation_functions.php');

    //here's a nice way to get home directories when several people are doing work on diff servers
    require $_SERVER['DOCUMENT_ROOT'].'/../amd-config.php';

    try {
        //instantiate a PDO database object
        $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
       /* echo "\nSuccessful.";*/
    } catch (PDOException $e) {
        echo "\nError connecting to DB " . $e->getMessage();
    }

    session_start();
    /*var_dump($_SESSION);*/

    //create an instance of the Base class for fat free
    $f3 = Base::instance();
    $con = new Controller($f3);

    $f3->route('GET /', function ()
    {
        $GLOBALS['con']->home();
    });

    $f3->route('GET|POST /my-account', function ($f3)
    {
        //initialize input variable(s) for sticky forms.
        $username = "";

        //if the form has been posted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (validUserName($username)) {
                //add data to session variable
                $_SESSION['username'] = $username;
            } else {
                $f3->set('errors["user"]', 'Please enter a valid username.');
            }

            if (validPassword($password)) {
                //add data to session variable
                $_SESSION['password'] = $password;
            } else {
                $f3->set('errors["pass"]', 'Please enter a valid password.');
            }

            //redirect user to next page if no errors
            if (empty($f3->get('errors'))) {
                $f3->reroute('admin');
            }
        }

        /*sticky username*/
        $f3->set('username', $username);

        $views = new Template();
        echo $views->render('views/my-account.html');
    });

    $f3->route('GET|POST /admin', function ($f3)
    {
        /*USER QUESTIONS FIRST*/
        //1. Define the query
        $sql = "SELECT * FROM user_questions";

        //2. Prepare the statement
        $statement = $GLOBALS['dbh']->prepare($sql);

        //3. Bind the parameters (if there are any)
        /*n/a*/

        //4. Execute the statement
        $statement->execute();

        //5. Process the result (if there is one)
        $row = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo $row['q_id']." ".$row['q_date']." ".$row['contact_email']." ".$row['user_name']." "
            .$row['q_text']." ".$row['is_answered']." ".$row['user_id'];

        $f3->set('questionStuff', $row);

        $views = new Template();
        echo $views->render('views/admin.html');
    });

    $f3->route('GET|POST /customer', function ()
    {
        $views = new Template();
        echo $views->render('views/customer.html');
    });

    $f3->route('GET|POST /cart', function ()
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

//send output to the browser
    ob_flush();