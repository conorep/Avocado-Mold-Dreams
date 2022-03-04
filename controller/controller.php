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
            $username = "";

            //if the form has been posted
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $username = $_POST['username'];
                $password = $_POST['password'];

                // this was dummy validation. keeping for testing.
                /*if (ValidationFunctions::validUserName($username)) {
                    //add data to session variable
                    $_SESSION['username'] = $username;
                } else {
                    $this->_f3->set('errors["user"]', 'Please enter a valid username.');
                }

                if (ValidationFunctions::validPassword($password)) {
                    //add data to session variable
                    $_SESSION['password'] = $password;
                } else {
                    $this->_f3->set('errors["pass"]', 'Please enter a valid password.');
                }*/

                $validateEmail = $GLOBALS['dataLayer']->checkEmailExistence($username);
                if($validateEmail == "") {
                    $this->_f3->set('errors["user"]', 'Please enter a valid email.');
                } else {

                    $validatePass = $GLOBALS['dataLayer']->checkPass($password);
                    if($validatePass == "") {
                        $this->_f3->set('errors["pass"]', 'Please enter a valid password.');
                    } else {
                        echo "<p>". $validatePass['user_email'] . "</p>";
                    }
                }


                //redirect user to next page if no errors
                //TODO: need to differentiate between admin and customer
                if (empty($this->_f3->get('errors'))) {
                    $this->_f3->reroute('admin');
                }
            }

            /*sticky username*/
            $this->_f3->set('username', $username);

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