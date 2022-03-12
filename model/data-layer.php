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

        //generic get-all-user-questions method
        function getUserQuestions()
        {
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

        function getShippingAddresses($userID)
        {
            $sql = "SELECT * FROM shipping_info WHERE user_id = :userid";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userid', $userID);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function getOrders()
        {
            $sql = "SELECT * FROM orders";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function getOrderItems($orderID)
        {
            $sql = "SELECT o.item_id, o.buy_qty, p.price 
                            FROM order_items o, product p
                            INNER JOIN ON o.item_id = p.item_id
                            WHERE o.order_id = :orderID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':orderID', $orderID);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        function getOrderTotal($orderID)
        {
            $sql = "SELECT * from order_items o
                        INNER JOIN product p ON o.item_id = p.item_id
                        WHERE o.order_id = :orderid";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':orderid', $orderID);
            $statement->execute();
            $items = $statement->fetchAll(PDO::FETCH_ASSOC);
            $sum = 0;
            foreach ($items as $item)
            {
                $sum += $item['buy_qty'] * $item['price'];
            }
            return $sum;
        }

        function completeOrder($orderID)
        {
            $sql = "UPDATE orders SET is_fulfilled = 1, fulfilled_date = :fdate WHERE order_id = :orderid";
            $statement = $this->_dbh->prepare($sql);
            $thisdate = date("Y-m-d H:i:s");
            $statement->bindParam(':orderid', $orderID);
            $statement->bindParam(':fdate', $thisdate);
            $statement->execute();
        }

        function changeUserType($userId, $number)
        {
            $sql = "UPDATE users SET is_admin = :adminnum WHERE user_id = :userid";

            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userid', $userId);
            $statement->bindParam(':adminnum', $number);

            $statement->execute();
        }


        //TODO: set a qty in item_stock table after this. need item_id, though
        function addNewItem($itemName, $itemPrice, $itemDesc, $sendName)
        {
            $sql = "INSERT INTO product(item_name, price, description, pic_link)
                    VALUES(:itemname, :itemprice, :itemdesc, :piclink)";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':itemname', $itemName);
            $statement->bindParam(':itemprice', $itemPrice);
            $statement->bindParam(':itemdesc', $itemDesc);
            $statement->bindParam(':piclink', $sendName);

            $statement->execute();
        }

        function addItemQty()
        {

        }

        function archiveItem()
        {

        }

        /**
         * Create a new user and add them to the database.
         * @param $useremail
         * @param $userphone
         * @param $fname
         * @param $lname
         * @param $pass
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