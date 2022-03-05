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

            /*var_dump($rows);*/

            $views = new Template();
            echo $views->render('views/home.html');
        }

        function admin()
        {
            //should I make a sub method for home and admin to use...?
            $rows = $GLOBALS['dataLayer']->getItems();
            $this->_f3->set('amdProducts', $rows);

            //user questions stuff for table display
            $rows = $GLOBALS['dataLayer']->getUserQuestions();
            $this->_f3->set('questionStuff', $rows);

            $rows = $GLOBALS['dataLayer']->getUsers();
            $this->_f3->set('userTableStuff', $rows);

            $views = new Template();
            echo $views->render('views/admin.html');
        }

        function accountLogin()
        {
            //initialize input variable(s) for sticky forms.
            $usermail = "";
            $newfname ="";
            $newlname = "";
            $newemail = "";
            $newphone = "";
            $newpass = "";


            //if the form has been posted
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['postbtn'] == 'login') {
                $usermail = $_POST['username'];
                $password = $_POST['password'];

                //check if the email address is in the system
                $validateEmail = $GLOBALS['dataLayer']->checkEmailExistence($usermail);

                if($validateEmail == "") {
                    //if email not in db, error code
                    $this->_f3->set('errors["user"]', 'Please enter a valid email address.');
                } else {
                    //email exists in db, check against it for password accuracy
                    $validatePass = $GLOBALS['dataLayer']->checkPass($password);

                    if($validatePass['user_email'] !== $usermail) {
                        //if password doesn't match email address pass, error code
                        $this->_f3->set('errors["pass"]', 'Please enter a valid password.');
                    } else {

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
                $newfname = stripslashes($_POST['newfname']);
                $newlname = stripslashes($_POST['newlname']);
                $newemail = stripslashes($_POST['newemail']);
                $newphone = stripslashes($_POST['newphone']);
                $newpass = stripslashes($_POST['newpass']);

                $validNewEmail = $GLOBALS['dataLayer']->checkEmailExistence($newemail);

                //check if the email address is in the system
                if(trim($newemail) == "" || $newemail == null) {
                    $this->_f3->set('errors2["newemail"]', 'Email address is required.');
                } elseif($validNewEmail != "") {
                    $this->_f3->set('errors2["newemail"]', 'This email address is already registered.');
                }

                if(!ValidationFunctions::validUserName($newfname)) {
                    $this->_f3->set('errors2["fname"]', 'First name must be longer than three letters.');
                }

                if(!ValidationFunctions::validUserName($newlname)) {
                    $this->_f3->set('errors2["lname"]', 'Last name must be longer than three letters.');
                }

                if(empty($this->_f3->get('errors2'))) {
                    $GLOBALS['dataLayer']->makeNewUser($newemail, $newphone, $newfname, $newlname, $newpass);
                    $this->_f3->set('usermade["newusermade"]', 'Account successfully made. Login to continue.');
                    //blank them again
                    $newfname ="";
                    $newlname = "";
                    $newemail = "";
                    $newphone = "";
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
            echo $views->render('views/customer.html');
        }

        function cart()
        {
            $views = new Template();
            echo $views->render('views/shopping-cart.html');
        }


    }