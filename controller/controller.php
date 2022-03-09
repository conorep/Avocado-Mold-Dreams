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
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                    // Check if image file is actual or fake image
                    if(isset($_POST["submit"])) {
                        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                        if($check !== false) {
                            echo "File is an image - " . $check["mime"] . ".";
                            $uploadOk = 1;
                        } else {
                            echo "File is not an image.";
                            $uploadOk = 0;
                        }
                    }
                    // Check if file already exists
                    if (file_exists($target_file)) {
                        echo "Sorry, file already exists.";
                        $uploadOk = 0;
                    }
                    // Check file size
                    if ($_FILES["fileToUpload"]["size"] > 500000) {
                        echo "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
                        $uploadOk = 0;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
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
                    }

                }

                /*sticky forms*/
                $this->_f3->set('uploadName', $itemName);
                $this->_f3->set('uploadPrice', $itemPrice);
                $this->_f3->set('uploadCount', $itemQty);

                echo $views->render('views/admin.html');
            }
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