<?php

    //328/Avocado-Mold-Dreams/controller/controller.php
    //NOTE: adminorcusty var is used to check whether to display a link to login page. pages with a 0 will have the account login route button visible
    class Controller
    {
        private $_f3; //f3 object

        function __construct($f3)
        {
            $this->_f3 = $f3;
        }

        /**
         * This function destroys the session. Activated by clicking the logout button on any given page.
         * @return void
         */
        function logOut()
        {
            session_destroy();
            $this->_f3->reroute('my-account');
        }

        /**
         * This function routes to the home page.
         * @return void
         */
        function home()
        {
            $_SESSION['adminOrCusty'] = 0;
            //call modal method
            $this->modalOps();

            //if add to cart button or other submit is clicked
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){

                //how to get item from post array?

            }

            $rows = $GLOBALS['dataLayer']->getItems();
            $this->_f3->set('amdProducts', $rows);
            //print_r($rows);

            $views = new Template();
            echo $views->render('views/home.html');
        }

        /**
         * This function routes to the account login page, pulls associated data, and uses functions from the datalayer
         * and validation functions objects.
         * @return void
         */
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

            //call modal method
            $this->modalOps();

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

        /**
         * This function routes to the admin page and calls for data to fill its tables,
         * handles the calls for new/archived admins, etc.
         * @return void
         */
        function admin()
        {
            $views = new Template();

            //check to see if anyone is logged in.
            //if yes, send them to appropriate section - don't allow access to admin page if admin not logged in.
            if (isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']->getIsAdmin() == 1) {
                $_SESSION['adminOrCusty'] = 1;

                //call modal method
                $this->modalOps();

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
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    //item upload code
                    if ($_POST['submit'] == 'itemUpload') {
                        //call to item add method
                        $itemName = stripslashes($_POST['uploadName']);
                        $itemPrice = stripslashes($_POST['uploadPrice']);
                        $itemDesc = stripslashes($_POST['uploadDescription']);
                        $itemQty = stripslashes($_POST['uploadCount']);

                        //if itemLoad returns true, reset those vars to empty
                        if($this->itemLoad($itemName, $itemPrice, $itemDesc, $itemQty)) {
                            $itemName = ""; $itemPrice = ""; $itemQty = "";
                        }
                    }

                    //question response code
                    if($_POST['submitQ']) {
                        $hiddenQID = substr($_POST['answers'], 7);
                        $userAnswer = $_POST[$hiddenQID];

                        if($_POST[$hiddenQID] == "") {
                            $this->_f3->set('errorsAns["blankAnswer"]', "Please enter an answer before submitting.");
                        } else {
                            $GLOBALS['dataLayer']->answerUserQuestion($userAnswer, $hiddenQID);
                            $this->_f3->reroute('admin');
                        }
                    }

                    //item archive code
                    if ($_POST['submit'] == 'archiveItem') {
                        $GLOBALS['dataLayer']->archiveItem($_POST['archiveOrDelete']);
                        $this->_f3->reroute('admin');
                    }

                    //item delete code
                    if ($_POST['submit'] == 'deleteItem') {
                        $GLOBALS['dataLayer']->deleteItem($_POST['archiveOrDelete']);
                        $this->_f3->reroute('admin');
                    }

                    //admin update code
                    if ($_POST['submit'] == 'adminUpdate') {
                        $GLOBALS['dataLayer']->changeUserType($_POST['addition'], 1);
                        $this->_f3->reroute('admin');
                    }
                    //admin remove code
                    if ($_POST['submit'] == 'adminRemove') {
                        $GLOBALS['dataLayer']->changeUserType($_POST['removal'], 0);
                        $this->_f3->reroute('admin');
                    }
                    //filled order code
                    if ($_POST['submit'] == 'orderFill') {
                        $GLOBALS['dataLayer']->completeOrder($_POST['fulfill']);
                        $this->_f3->reroute('admin');
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

        /**
         * This function routes to the customer page.
         * @return void
         */
        function customer()
        {
            $views = new Template();
            if (isset($_SESSION['loggedUser'])) {
                $_SESSION['adminOrCusty'] = 1;

                //call modal method
                $this->modalOps();

                if ($_SESSION['loggedUser']->getIsAdmin() == 0) {
                    //do customer stuff here!

                    //orders for table
                    $rows = $GLOBALS['dataLayer']->getUserOrder($_SESSION['loggedUser']->getUserID());
                    $this->_f3->set('customersOrders', $rows);

                    foreach($rows as $aRow) {
                        $thisKey = $aRow['order_id'];
                        $this->_f3->set($thisKey, $aRow);
                    }

                    //user addresses
                    $addys = $GLOBALS['dataLayer']->getShippingAddresses($_SESSION['loggedUser']->getUserID());
                    $this->_f3->set('customerAddys', $addys);

                    //user's questions
                    $custQs = $GLOBALS['dataLayer']->getThisUsersQuestions($_SESSION['loggedUser']->getUserID());
                    $this->_f3->set('custQuestions', $custQs);

                    /*TODO: need to do validation on ALL OF THIS*/
                    if($_POST['submit'] == 'userChanger') {
                        $userID = $_SESSION['loggedUser']->getUserID();
                        $updateValue = $_POST['updateInfo'];

                        /* call function: using user_id, column_name, and value */
                        if($_POST['changeSelect'] == 'fname') {
                            $_SESSION['loggedUser']->setFname($_POST['updateInfo']);
                            /*$GLOBALS['dataLayer']->updateUser($userID, 'f_name', $updateValue);*/
                            $GLOBALS['dataLayer']->updateUserFname($userID, $updateValue);

                            $this->_f3->reroute('customer');
                        }
                        else if ($_POST['changeSelect'] == 'lname') {
                            $_SESSION['loggedUser']->setLname($_POST['updateInfo']);
                            /*$GLOBALS['dataLayer']->updateUser($userID, 'l_name', $updateValue);*/
                            $GLOBALS['dataLayer']->updateUserLname($userID, $updateValue);

                            $this->_f3->reroute('customer');
                        }
                        else if ($_POST['changeSelect'] == 'email') {
                            $_SESSION['loggedUser']->setEmail($_POST['updateInfo']);
                            /*$GLOBALS['dataLayer']->updateUser($userID, 'user_email', $updateValue);*/
                            $GLOBALS['dataLayer']->updateUserEmail($userID, $updateValue);

                            $this->_f3->reroute('customer');
                        }
                        else if ($_POST['changeSelect'] == 'phone') {
                            $_SESSION['loggedUser']->setPhoneNum($_POST['updateInfo']);
                            /*$GLOBALS['dataLayer']->updateUser($userID, 'user_phone', $updateValue);*/
                            $GLOBALS['dataLayer']->updateUserPhone($userID, $updateValue);

                            $this->_f3->reroute('customer');
                        }
                    }

                    //TODO: THESE TWO
                    if($_POST['submit'] == 'newaddressSub') {

                    }

                    if($_POST['submit'] == 'deladdressSub') {

                    }

                    echo $views->render('views/customer.html');
                } else if ($_SESSION['loggedUser']->getIsAdmin() == 1) {
                    $this->_f3->reroute('admin');
                }
            } else {
                    $this->_f3->reroute('my-account');
            }
        }

        /**
         * This function routes to the cart page.
         * @return void
         */
        function cart()
        {
            $_SESSION['adminOrCusty'] = 0;
            //call modal method
            $this->modalOps();

            $productArr = $_SESSION['sessionCart']->getInCartArr();

            //if there's a cart object in the session, generate a cart page

            $cartRows = $GLOBALS['dataLayer']->getItemsForCart($productArr);
            $this->_f3->set('cartItems', $cartRows);

/*            echo("<br><br>product array: <BR>");
            print_r($productArr);
            echo("<br><br>products returned from db: <BR>");
            print_r($cartRows);*/

            $views = new Template();
            echo $views->render('views/shopping-cart.html');
        }

        /**
         * This function does verifications for the modal that can be used in any given window.
         * @return void
         */
        private function modalOps()
        {
            $qUserName = "";
            $userQuestionEmail = "";
            $qUserID = null;

            //sticky form vars. if there's an active user, get the values.
            if(isset($_SESSION['loggedUser'])) {
                $qUserName = $_SESSION['loggedUser']->getFname() . " " . $_SESSION['loggedUser']->getLname();
                $userQuestionEmail = $_SESSION['loggedUser']->getEmail();
                $qUserID = $_SESSION['loggedUser']->getUserID();
            }
            //submit stuff from modal question form
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'questionSubmit'){

                $userQuestion = stripslashes($_POST['questionText']);
                $qUserName = stripslashes($_POST['questionUser']);
                $userQuestionEmail = stripslashes($_POST['questionEmail']);

                if($userQuestion == "" || !isset($_POST['questionText'])) {
                    $this->_f3->set('errorsQuestion["questionTextErr"]', 'Please enter a question.');
                }
                if($qUserName == "" || !isset($_POST['questionUser'])) {
                    $this->_f3->set('errorsQuestion["questionUserError"]', 'Please enter a contact name.');
                }
                if($userQuestionEmail == "" || !isset($_POST['questionEmail']) ||
                    !ValidationFunctions::validEmail($userQuestionEmail)) {
                    $this->_f3->set('errorsQuestion["questionEmailErr"]', 'Please enter a contact email.');
                }

                if(empty($this->_f3->get('errorsQuestion'))) {
                    $GLOBALS['dataLayer']->addNewQuestion($userQuestionEmail, $qUserName, $userQuestion, $qUserID);
                    $qUserName = "";
                    $userQuestionEmail = "";
                }

            }
            $this->_f3->set('questionEmailVal', $userQuestionEmail);
            $this->_f3->set('questionUserNameVal', $qUserName);
        }

        /**
         * This private function handles verifications of the photo upload for new items. If it worked, return true,
         * otherwise return false.
         * @return bool
         */
        private function itemLoad($itemName, $itemPrice, $itemDesc, $itemQty)
        {
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
                    $this->_f3->set('picUp["newPicUp"]', 'Picture good for upload.');
                } else {
                    $this->_f3->set('picUp["noPicUp"]', 'Error. Picture did not upload.');
                }
            }

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

            if(empty($this->_f3->get('errors')) && empty($this->_f3->get('errorsPic'))) {
                $GLOBALS['dataLayer']->addNewItem($itemName, $itemPrice, $itemDesc, $sendName);
                $GLOBALS['dataLayer']->addItemQty($GLOBALS['dataLayer']->maxID(), $itemQty);
                $this->_f3->set('picUp["newPicUp"]', "Picture good for upload.\nItem added successfully.");
                return true;
            }
            return false;
        }

    }