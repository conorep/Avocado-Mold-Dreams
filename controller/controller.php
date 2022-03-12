<?php

    //328/Avocado-Mold-Dreams/controller/controller.php
    class Controller
    {
        private $_f3; //f3 object

        function __construct($f3)
        {
            $this->_f3 = $f3;
        }

        function logOut()
        {
            session_destroy();
            $this->_f3->reroute('my-account');
        }

        function home()
        {
            $_SESSION['adminOrCusty'] = 0;
            $rows = $GLOBALS['dataLayer']->getItems();
            $this->_f3->set('amdProducts', $rows);
            print_r($rows);
            //if add to cart button is clicked?
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                //how to get item from post array?
            }

            $views = new Template();
            echo $views->render('views/home.html');
        }

        function admin()
        {
            $views = new Template();

            //check to see if anyone is logged in.
            //if yes, send them to appropriate section - don't allow access to admin page if admin not logged in.
            if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']->getIsAdmin() == 1) {
                $_SESSION['adminOrCusty'] = 1;
                //vars for sticky forms
                $itemName = ""; $itemPrice = ""; $itemQty = "";

                //should I make a sub method for home and admin to use...?
                $rows = $GLOBALS['dataLayer']->getItems();
                $this->_f3->set('amdProducts', $rows);

                //orders for table
                $rows = $GLOBALS['dataLayer']->getOrders();
                $this->_f3->set('amdOrders', $rows);

                //user questions stuff for table display
                $rows = $GLOBALS['dataLayer']->getUserQuestions();
                $this->_f3->set('questionStuff', $rows);

                //users for table
                $rows = $GLOBALS['dataLayer']->getUsers();
                $this->_f3->set('userTableStuff', $rows);

                //stuff for adding new item to db inventory
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'itemUpload') {

                    //TODO: move all of the pic import stuff to its own method and stop echoing. add errors
                    $sendName = basename($_FILES["uploadPic"]["name"]);
                    $target_file = "images/inventory/". basename($_FILES["uploadPic"]["name"]);
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                    // Check if image file is actual or fake image
                    if(isset($_POST["submit"])) {
                        $check = getimagesize($_FILES["uploadPic"]["tmp_name"]);
                        if(!$check) {
                            $this->_f3->set('errorsPic["realImage"]', 'This does not appear to be a picture.');
                        }
                    }
                    // Check if file already exists here. IS THIS NEEDED?
                    if (file_exists($target_file)) {
                        $this->_f3->set('errorsPic["fileExists"]', 'This image already exists at this domain.');
                    }
                    // Check file size
                    if ($_FILES["uploadPic"]["size"] > 500000) {
                        $this->_f3->set('errorsPic["imageSize"]', 'Your image cannot be bigger than 500kb.');
                    }
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        $this->_f3->set('errorsPic["imageType"]', 'Your picture must be jpg, png, or jpeg.');
                    }
                    // if no image errors,
                    if (empty($this->_f3->get('errorsPic'))) {
                        if (move_uploaded_file($_FILES["uploadPic"]["tmp_name"], $target_file)) {
                            $this->_f3->set('picUp["newPicUp"]', 'Picture uploaded successfully.');
                        } else {
                            $this->_f3->set('picUp["noPicUp"]', 'Error. Picture did not upload.');
                        }
                    }

                    $itemName = stripslashes($_POST['uploadName']);
                    $itemPrice = stripslashes($_POST['uploadPrice']);
                    $itemDesc = stripslashes($_POST['uploadDescription']);
                    $itemQty = stripslashes($_POST['uploadCount']);

                    if($itemName == "") {
                        $this->_f3->set('errors["emptyName"]', 'Item name empty.');
                    }
                    if($itemPrice == "") {
                        $this->_f3->set('errors["emptyPrice"]', 'Item price empty.');
                    }
                    if($itemDesc == "") {
                        $this->_f3->set('errors["emptyDesc"]', 'Item description empty.');
                    }
                    if($itemQty == "") {
                        $this->_f3->set('errors["emptyQty"]', 'Item qty empty.');
                    }

                    if(empty($this->_f3->get('errors'))) {
                        $GLOBALS['dataLayer']->addNewItem($itemName, $itemPrice, $itemDesc, $sendName);
                        $itemName = ""; $itemPrice = ""; $itemQty = "";
                    }
                }
                /*sticky forms*/
                $this->_f3->set('uploadName', $itemName);
                $this->_f3->set('uploadPrice', $itemPrice);
                $this->_f3->set('uploadCount', $itemQty);

                echo $views->render('views/admin.html');
            }
            //TODO: next up create the actual admin
/*            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'adminUpdate') {

            }*/
            //if not admin, send to user page if there's a login active in session
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
            $newfname = "";
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
                $_SESSION['adminOrCusty'] = 1;
                if ($_SESSION['loggedUser']->getIsAdmin() == 0) {
                    //do customer stuff here!


                    echo $views->render('views/customer.html');
                } else if ($_SESSION['loggedUser']->getIsAdmin() == 1) {
                    $this->_f3->reroute('admin');
                }
            } else {
                    $this->_f3->reroute('my-account');
            }
        }

        function cart()
        {
            $_SESSION['adminOrCusty'] = 0;
            $views = new Template();
            echo $views->render('views/shopping-cart.html');
        }


    }