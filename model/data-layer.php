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

        /**
         * This method returns all items from the database.
         * @return array|false array of items, or none if there aren't any
         */
        function getItems()
        {
            $sql = "SELECT * FROM product";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This function retrieves one specific item's name.
         * @param $itemID number itemid to search by
         * @return string name of item
         */
        function getThisItem($itemID)
        {
            $sql = "SELECT * FROM product WHERE item_id = :itemID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':itemID', $itemID);
            $statement->execute();
            $itemName = $statement->fetch(PDO::FETCH_ASSOC);
            return $itemName['item_name'];
        }

        /**
         * This method returns all user questions in the database.
         * @return array|false array of questions or none if there aren't any
         */
        function getUserQuestions()
        {
            $sql = "SELECT * FROM user_questions";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This method returns all user questions associated with one user in the database.
         * @return array|false array of questions or none if there aren't any
         */
        function getThisUsersQuestions($userID)
        {
            $sql = "SELECT * FROM user_questions WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userID', $userID);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This function fills in an admin's answer to a user question and marks it answered.
         * @param $ansText
         * @param $qID
         * @return void
         */
        function answerUserQuestion($ansText, $qID)
        {
            $sql = "UPDATE user_questions SET q_answer = :anstext, is_answered = 1 WHERE q_id = :qID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':anstext', $ansText);
            $statement->bindParam(':qID', $qID);

            $statement->execute();
        }

        /**
         * This method returns all users from the database.
         * @return array|false array of users, or nothing if there are none.
         */
        function getUsers()
        {
            $sql = "SELECT * FROM users";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This method returns a user's shipping addresses from the database.
         * @param $userID mixed ID to search by
         * @return array|false array of all known addresses or none if there aren't any
         */
        function getShippingAddresses($userID)
        {
            $sql = "SELECT * FROM shipping_info WHERE user_id = :userid";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userid', $userID);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This method retrieves orders from the database.
         * @return array|false array of all orders, or none if there aren't any
         */
        function getOrders()
        {
            $sql = "SELECT * FROM orders";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This method retrieves all orders for a certain customer.
         * @param $userID number the id to pull all associated order by
         * @return array|false array of orders if there are some, otherwise nothing
         */
        function getUserOrder($userID)
        {
            $sql = "SELECT * FROM orders WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userID', $userID);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This method gathers all items associated with an order number and returns them.
         * @param $orderID number order num to search for associated items
         * @return array|false array of the items, or nothing if no items
         */
        function getOrderItems($orderID)
        {
            $sql = "SELECT * FROM order_items WHERE order_id = :orderID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':orderID', $orderID);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This method returns a cash total of the items in an order. This adds 10% tax to the total and returns that.
         * @param $orderID mixed order id to search my
         * @return float|int number return of the sum of all order items
         */
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
            /*add tax!*/
            $sum += $sum * 0.1;
            return $sum;
        }

        /**
         * This method allows marking an order as fulfilled.
         * @param $orderID mixed id to set as fulfilled
         * @return void
         */
        function completeOrder($orderID)
        {
            $sql = "UPDATE orders SET is_fulfilled = 1, fulfilled_date = :fdate WHERE order_id = :orderid";
            $statement = $this->_dbh->prepare($sql);
            $thisdate = date("Y-m-d H:i:s");
            $statement->bindParam(':orderid', $orderID);
            $statement->bindParam(':fdate', $thisdate);
            $statement->execute();
        }

        /**
         * This method allows changing of account types to admin or customer.
         * @param $userId mixed user if to mark as admin or customer
         * @param $number number 0 for customer 1 for admin
         * @return void
         */
        function changeUserType($userId, $number)
        {
            $sql = "UPDATE users SET is_admin = :adminnum WHERE user_id = :userid";

            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userid', $userId);
            $statement->bindParam(':adminnum', $number);

            $statement->execute();
        }


        //TODO: set a qty in item_stock table after this. need item_id, though

        /**
         * This method allows addition of new items to the database.
         * @param $itemName string item name
         * @param $itemPrice number item price
         * @param $itemDesc string item description
         * @param $sendName string pic link
         * @return void
         */
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

        /**
         * This method allows addition of a new question to the database.
         * @param $contactEmail user's provided contact email
         * @param $userName user's provided contact name
         * @param $qText user's provided question
         * @param $userID user's ID if logged in
         * @return void
         */
        function addNewQuestion($contactEmail, $userName, $qText, $userID)
        {
            $sql = "INSERT INTO user_questions(contact_email, user_name, q_text, user_id)
                    VALUES(:contactemail, :username, :qtext, :userid)";
            $statement = $this->_dbh->prepare($sql);

            $statement->bindParam(':contactemail', $contactEmail);
            $statement->bindParam(':username', $userName);
            $statement->bindParam(':qtext', $qText);
            $statement->bindParam(':userid', $userID);

            $statement->execute();

        }

        //TODO: make an admin function to alter existing stock nums.
        /**
         * This function changes the qty of the item reflecting the itemID sent in.
         * @param $itemID number the item ID to alter
         * @param $itemQTY number how much to make the stock reflect
         * @return void
         */
        function changeItemQty($itemID, $itemQTY)
        {
            $sql = "UPDATE item_stock SET stck_num = :stocknum WHERE item_id = :itemID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':stocknum', $itemQTY);
            $statement->bindParam(':itemID', $itemID);

            $statement->execute();
        }

        //TODO: NEED TO CHECK IF THERE IS ALREADY ITEM STOCK WHEN USING THIS
        /**
         * This function add an item and its quantity into the database.
         * @param $itemID number id of item to add
         * @param $itemQTY number quantity to add
         * @return void
         */
        function addItemQty($itemID, $itemQTY)
        {
            $sql = "INSERT INTO item_stock (item_id, item_stock)
                    VALUES(:itemID, :itemQTY)";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':itemID', $itemID);
            $statement->bindParam(':itemQTY', $itemQTY);

            $statement->execute();
        }

        /**
         * This method returns the highest item id in the products table. Used for retrieving
         * the latest-added product.
         * @return mixed item_id
         */
        function maxID()
        {
            $sql = "SELECT MAX(item_id) FROM products";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC)['item_id'];
        }


        /**
         * This function returns a stock number for an item.
         * @param $itemID number used for finding associated stock num
         * @return mixed the stock num for that ID
         */
        function getItemQty($itemID)
        {
            $sql = "SELECT * FROM item_stock WHERE item_id = :itemID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':itemID', $itemID);

            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC)['stock_num'];
        }

        /**
         * This method archives an item in the database.
         * @param $itemID number item to archive
         * @return void
         */
        function archiveItem($itemID)
        {
            $sql = "UPDATE product SET is_archived = 1 WHERE item_id = :itemID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':itemID', $itemID);

            $statement->execute();
        }

        /**
         * This function deletes an item from the database.
         * @param $itemID number item to delete
         * @return void
         */
        function deleteItem($itemID)
        {
            $sql = "DELETE FROM product WHERE item_id = :itemID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':itemID', $itemID);

            $statement->execute();
        }

        /**
         * Create a new user and add them to the database.
         * @param $useremail string
         * @param $userphone mixed
         * @param $fname string
         * @param $lname string
         * @param $pass mixed
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


        //TODO: MAKE THIS WORK.
        /**
         * Updates a specified column value of a specified user.
         * @param $userID mixed
         * @param $updateColumn mixed
         * @param $updateVal mixed
         * @return void
         */
        function updateUser($userID, $updateColumn, $updateVal)
        {
            $sql = "UPDATE users SET :updatecol = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updatecol', $updateColumn);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }

        function updateUserFname($userID, $updateVal)
        {
            $sql = "UPDATE users SET f_name = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }
        function updateUserLname($userID, $updateVal)
        {
            $sql = "UPDATE users SET l_name = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }
        function updateUserEmail($userID, $updateVal)
        {
            $sql = "UPDATE users SET user_email = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }
        function updateUserPhone($userID, $updateVal)
        {
            $sql = "UPDATE users SET user_phone = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
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

        /**
         * @param $cartArrayInput
         * @return array|false
         */
        function getItemsForCart($cartArrayInput)
        {
            $sqlString = implode(', ', $cartArrayInput);
            /*echo "<br><br>sqlString: ";
            print_r($sqlString);*/
            //need to select rows that match product ids from cart session

            $sql = "SELECT * FROM product WHERE item_id IN ($sqlString)";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(":cartArr", $sqlString);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

    }