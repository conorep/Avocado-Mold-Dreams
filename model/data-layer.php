<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/../amd-config.php';

    class DataLayer
    {
        //add field to store the db connection object
        private $_dbh;

        //define default constructor
        public function __construct()
        {
            try {
                //instantiate a PDO database object
                $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            } catch (PDOException $e) {
                echo "\nError connecting to DB " . $e->getMessage();
            }
        }

        //generic get-all-items method. can make it cooler with parameters (price range and such)
        function getItems()
        {
            $sql = "SELECT * FROM product";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function getUserQuestions()
        {
            //1. Define the query
            //2. Prepare the statement
            //3. Bind the parameters (if there are any)
            //4. Execute the statement
            //5. Process the result (if there is one), return

            $sql = "SELECT * FROM user_questions";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function getUsers()
        {
            $sql = "SELECT * FROM users";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function addNewItem()
        {

        }

        function archiveItem()
        {

        }

        //TODO: THIS NEEDS TO ESSENTIALLY MAKE A USER OBJECT
        //TODO: make sure you check for an existing email first before making another user with that email
        function makeNewUser()
        {
            $sql = "INSERT INTO users(isAdmin, userEmail, user_phone, f_name, l_name, hash_pass)
                    VALUES(0, :email, :phoneNum, :fname, :lname, :hashpass)";
            $statement = $this->_dbh->prepare($sql);

            //hash the user's password
            $hashedPass = $this->hashPass($_SESSION['AMDuserPass']);

            $statement->bindParam(':email', $_SESSION['AMDemail']);
            $statement->bindParam(':phoneNum', $_SESSION['AMDphoneNumber']);
            $statement->bindParam(':fname', $_SESSION['AMDfname']);
            $statement->bindParam(':lname', $_SESSION['AMDlname']);
            $statement->bindParam(':hashpass', $hashedPass);

            $statement->execute();
        }

        function addNewAdmin()
        {

        }

        // whether this returns true or false it will be helpful.
        // makenewuser needs it to return false, checkpass needs it to return true
        function checkEmailExistence($userEmail)
        {
            $sql = "SELECT user_id FROM users WHERE user_email = :useremail";
            $statement = $this->_dbh->prepare($sql);
            $userEmail = stripslashes($userEmail);
            $statement->bindParam(':useremail', $userEmail);

            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }


        //TODO: this should prob go in validation_functions...?
        function checkPass($userPass)
        {
            $sql = "SELECT user_email FROM users WHERE hash_pass = :hashpass";
            $statement = $this->_dbh->prepare($sql);

            //block some shady sql stuff, then hash the pass
            $userPass = stripslashes($userPass);
            $userPass = $this->hashPass($userPass);

            $statement->bindParam(':hashpass', $userPass);

            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        /*hash password with a salt (just gonna use the same every time for simplicity)*/
        function hashPass($userPass)
        {
            $userPass = $userPass . "OrchOtters";
            return hash("sha256", $userPass);
        }

    }