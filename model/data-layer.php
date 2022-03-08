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

        //TODO: javadoc this
        /**
         * @param $useremail
         * @param $userphone
         * @param $fname
         * @param $lname
         * @param $pass
         * @return void
         */
         function makeNewUser($useremail, $userphone, $fname, $lname, $pass)
        {
            $sql = "INSERT INTO users(is_admin, user_email, user_phone, f_name, l_name, hash_pass)
                    VALUES(0, :email, :phoneNum, :fname, :lname, :hashpass)";
            $statement = $this->_dbh->prepare($sql);

            //hash the user's password
            $hashedPass = $this->hashPass($pass);

            $statement->bindParam(':email', $useremail);
            $statement->bindParam(':phoneNum', $userphone);
            $statement->bindParam(':fname', $fname);
            $statement->bindParam(':lname', $lname);
            $statement->bindParam(':hashpass', $hashedPass);

            $statement->execute();
        }

        function addNewAdmin()
        {

        }

        /**
         * Send in user email entry to check database for. Return array with user_email for usage to check against
         * checkPass return values.
         * @param $userEmail string user form input email
         * @return mixed either empty or array with user_email
         */
        function getUser($userEmail)
        {
            $sql = "SELECT * FROM users WHERE user_email = :useremail";
            $statement = $this->_dbh->prepare($sql);

            //block some shady sql stuff, then hash the pass
            $userEmail = stripslashes($userEmail);
            $statement->bindParam(':useremail', $userEmail);

            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * Send in user password entry, hash it, then check database for it. Return array with associated user email
         * and is_admin for final verification and routing to admin or customer page.
         * @param $userPass string user form input password
         * @return mixed either empty or array with user_email and is_admin
         */
        function checkPass($userPass)
        {
            $sql = "SELECT user_email, is_admin FROM users WHERE hash_pass = :hashpass";
            $statement = $this->_dbh->prepare($sql);

            //block some shady sql stuff, then hash the pass
            $userPass = stripslashes($userPass);
            $userPass = $this->hashPass($userPass);

            $statement->bindParam(':hashpass', $userPass);

            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * This method takes an input user password and returns a hashed version. It appends a salt to the password
         * before hashing using sha256. Used for new user creation, as well as for validation for login.
         * @param $userPass string user password
         * @return string hashed password with salt
         */
        function hashPass($userPass)
        {
            $userPass = $userPass . "OrchOtters";
            return hash("sha256", $userPass);
        }

    }