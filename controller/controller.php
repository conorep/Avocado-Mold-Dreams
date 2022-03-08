<?php

    //328/Avocado-Mold-Dreams/controller/controller.php
    class Controller
    {
        private $_f3; //f3 object

        function __construct($f3)
        {
            $this->_f3 = $f3;
        }

        function home()
        {
            $rows = $GLOBALS['dataLayer']->getItems();
            $this->_f3->set('amdProducts', $rows);

            $views = new Template();
            echo $views->render('views/home.html');
        }

        function admin()
        {
            $views = new Template();

            //check to see if anyone is logged in. if yes, send them to appropriate section - don't allow access to
            //admin page if admin not logged in.
            if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']->getIsAdmin() == 1) {
                //should I make a sub method for home and admin to use...?
                $rows = $GLOBALS['dataLayer']->getItems();
                $this->_f3->set('amdProducts', $rows);

                //orders for table
                $rows = $GLOBALS['dataLayer']->getOrders();
                $this->_f3->set('amdOrders', $rows);

                //TODO: use this to populate Order class...?
                //items for table
/*                $rows = $GLOBALS['dataLayer']->getOrderItems();
                $this->_f3->set('amdTotals', $rows);*/

                //prices for table
/*                $price = $GLOBALS['dataLayer']->getOrderTotal(1);
                $this->_f3->set('dummyOrder1Total', $price);*/

                //user questions stuff for table display
                $rows = $GLOBALS['dataLayer']->getUserQuestions();
                $this->_f3->set('questionStuff', $rows);

                //users for table
                $rows = $GLOBALS['dataLayer']->getUsers();
                $this->_f3->set('userTableStuff', $rows);

                echo $views->render('views/admin.html');
            }
            else if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']->getIsAdmin() == 0) {
                $this->_f3->reroute('customer');
            }
            else {
                $this->_f3->reroute('my-account');
            }

        }

        function accountLogin()
        {
            if (isset($_SESSION['loggedUser'])) {
                if ($_SESSION['loggedUser']->getIsAdmin() == 0) {
                    $this->_f3->reroute('customer');
                } else if ($_SESSION['loggedUser']->getIsAdmin() == 1) {
                    $this->_f3->reroute('admin');
                }
            }

            //initialize input variable(s) for sticky forms.
            $usermail = "";
            $newfname ="";
            $newlname = "";
            $newemail = "";
            $newphone = "";

            //if the form has been posted
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['postbtn'] == 'login') {
                //LOGIN BUTTON POST AREA
                $usermail = $_POST['username'];
                $password = $_POST['password'];

                //check if the email address is in the system
                $retrieveUser = $GLOBALS['dataLayer']->getUser($usermail);

                if($retrieveUser == "") {
                    //if email not in db, error code
                    $this->_f3->set('errors["user"]', 'Please enter a valid email address.');
                } else {
                    //email exists in db, check against it for password accuracy
                    $validatePass = $GLOBALS['dataLayer']->checkPass($password);

                    if($validatePass['user_email'] !== $usermail) {
                        //if password doesn't match email address pass, error code
                        $this->_f3->set('errors["pass"]', 'Please enter a valid password.');
                    } else {

                        $_SESSION['loggedUser'] = new AMDUser($retrieveUser['user_id'], $retrieveUser['is_admin'],
                            $retrieveUser['user_email'], $retrieveUser['user_phone'], $retrieveUser['f_name'],
                            $retrieveUser['l_name'], $retrieveUser['hash_pass']);

                        //user_id 1 is for admins, user_id 0 is for custies
                        if($validatePass['is_admin'] == 1)
                        {
                            $this->_f3->reroute('admin');
                        } else {
                            $this->_f3->reroute('customer');
                        }
                    }
                }
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['postbtn'] == 'newuser') {
                //NEW USER BUTTON POST AREA
                $newfname = stripslashes($_POST['newfname']);
                $newlname = stripslashes($_POST['newlname']);
                $newemail = stripslashes($_POST['newemail']);
                $newphone = ValidationFunctions::stripPhone($_POST['newphone']);
                $newpass = stripslashes($_POST['newpass']);

                $this->_f3->set('display', 'd-block');
                $this->_f3->set('display2', 'd-none');

                $retrieveUser = $GLOBALS['dataLayer']->getUser($newemail);

                //check if the email address is in the system
                if(trim($newemail) == "" || $newemail == null) {
                    $this->_f3->set('errors2["newemail"]', 'Email address is required.');
                } elseif($retrieveUser != "") {
                    $this->_f3->set('errors2["newemail"]', 'This email address is already registered.');
                } elseif(!ValidationFunctions::validEmail($newemail)) {
                    $this->_f3->set('errors2["newemail"]', 'This is not a valid email address.');
                }

                if(!ValidationFunctions::validUserName($newfname)) {
                    $this->_f3->set('errors2["fname"]', 'First name must be longer than three letters.');
                }

                if(!ValidationFunctions::validUserName($newlname)) {
                    $this->_f3->set('errors2["lname"]', 'Last name must be longer than three letters.');
                }

                if(!ValidationFunctions::validPhone($newphone) && !empty($newphone)) {
                    $this->_f3->set('errors2["newphone"]', 'If entering a phone number, it must be 10 numbers long.');
                }

                if(empty($this->_f3->get('errors2'))) {
                    $GLOBALS['dataLayer']->makeNewUser($newemail, $newphone, $newfname, $newlname, $newpass);
                    $this->_f3->set('usermade["newusermade"]', 'Account successfully made. Login to continue.');
                    //blank them again
                    $newfname ="";
                    $newlname = "";
                    $newemail = "";
                    $newphone = "";

                    $this->_f3->set('display', 'd-none');
                    /*so i can either make the checkbox disappear after making new user, just reload page, or
                    figure something better*/
                    $this->_f3->set('check', '');
                }
            }

            /*sticky username*/
            $this->_f3->set('username', $usermail);
            $this->_f3->set('newfname', $newfname);
            $this->_f3->set('newlname', $newlname);
            $this->_f3->set('newemail', $newemail);
            $this->_f3->set('newphone', $newphone);

            $views = new Template();
            echo $views->render('views/my-account.html');
        }

        function customer()
        {
            $views = new Template();
            if (isset($_SESSION['loggedUser'])) {
                if ($_SESSION['loggedUser']->getIsAdmin() == 0) {
                    //do customer stuff here!


                    echo $views->render('views/customer.html');
                } else if ($_SESSION['loggedUser']->getIsAdmin() == 1) {
                    $this->_f3->reroute('admin');
                }
            } else {
                    echo $views->render('views/my-account.html');
            }
        }

        function cart()
        {
            $views = new Template();
            echo $views->render('views/shopping-cart.html');
        }


    }