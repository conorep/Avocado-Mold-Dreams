<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/../amd-config.php';

    /*This class is our DataLayer class, which insert/updates/deletes/otherwise alters the connected database.*/
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
         * This method returns a row of question info.
         * @return array|false array of questions or none if there aren't any
         */
        function getThisQuestion($qID)
        {
            $sql = "SELECT * FROM user_questions WHERE q_id = :qid";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':qid', $qID);
            $statement->execute();

            return $statement->fetch();
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
         * This function sends an email response to the user.
         * @param $userEmail
         * @param $username
         * @param $userquestion
         * @param $qresponse
         * @return void
         */
        function emailQuestionUser($userEmail, $username, $userquestion, $qresponse)
        {
            $mailtext = 'Hello, ' . $username .'. Please see the answer to your question: '."\nUser Question: " . $userquestion . "\nAnswer: " . $qresponse;
            $mailtext = wordwrap($mailtext, 70);

            mail($userEmail, 'Avocado Mold Dreams QandA', $mailtext, 'AMD Answer Contained:');
        }

        /**
         * This function sends an email when an order is marked fulfilled.
         * @param $orderID number user id
         * @return void
         */
        function emailCustomer($orderID)
        {
            $row = $this->getInfo($orderID);

            $mailtext = 'Hello, ' . $row['f_name'] .'. Your order (order ID #' . $row['order_id'] . ") has shipped. Thank you for shopping with us! Avocado Mold Dreams hopes to see you again!";

            $mailtext = wordwrap($mailtext, 70);
            mail($row['user_email'], 'AMD Order '. $row['order_id'] . ' Completed', $mailtext, 'AMD Order Info Contained:');
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
         * This function inserts new shipping locations into the ship_locations table.
         * @param $userID number
         * @param $shipLoc string
         * @return void
         */
        function setShippingAddresses($userID, $shipLoc)
        {
            $sql = "INSERT INTO shipping_info (ship_location, user_id) VALUES (:shipLoc, :userID)";
            $statement = $this->_dbh->prepare($sql);

            $statement->bindParam(':shipLoc', $shipLoc);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }

        /**
         * This function deletes a shipping location from the ship_locations table.
         * @param $userID number
         * @param $shipLoc string
         * @return void
         */
        function deleteShippingAdd($userID, $shipLoc)
        {
            $sql = "DELETE FROM shipping_info WHERE ship_location = :shiploc AND user_id = :userid";
            $statement = $this->_dbh->prepare($sql);

            $statement->bindParam(':shiploc', $shipLoc);
            $statement->bindParam(':userid', $userID);

            $statement->execute();
        }

        /**
         * This function updates a user's password.
         * @param $userID
         * @param $pass
         * @return void
         */
        function updatePass($userID, $pass)
        {
            $sql = "UPDATE users SET hash_pass = :hashpass WHERE user_id = :userid";
            $statement = $this->_dbh->prepare($sql);
            $newPass = $this->hashPass($pass);

            $statement->bindParam(':hashpass', $newPass);
            $statement->bindParam(':userid', $userID);
        }

        /**
         * This method retrieves orders from the database.
         * @return array|false array of all orders, or none if there aren't any
         */
        function getOrders()
        {
            $sql = "SELECT * FROM orders o LEFT JOIN users u ON o.user_id = u.user_id";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * This function returns a user ID associated with an order ID.
         * @param $orderID
         * @return number|false
         */
        function getUserID($orderID)
        {
            $sql = "SELECT user_id FROM orders WHERE order_id = :orderID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userid', $userID);
            $statement->execute();

            return $statement->fetchColumn();
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
         * @param $orderID mixed order id to search by
         * @param $ispremium mixed premium or not
         * @param $prempercent mixed percentage discount
         * @return float|int number return of the sum of all order items
         */
        function getOrderTotal($orderID, $ispremium, $prempercent)
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

            if($ispremium == 1) {
                $sum -= $sum * $prempercent;
            }
            /*add tax!*/
            $sum += $sum * 0.1;
            return number_format($sum, 2);
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
         * This function gets user info referenced against an orderID.
         * @param $orderID
         * @return mixed
         */
        function getInfo($orderID)
        {
            $sql = "SELECT * FROM orders o LEFT JOIN users u ON o.user_id = u.user_id WHERE order_id = :orderID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':orderID', $orderID);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
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

        /**
         * This method creates or removes a premium user status.
         * @param $userID
         * @param $makeTake
         * @return void
         */
        function makeOrTakePrem($userID, $makeTake)
        {
            $sql = "UPDATE users SET is_premium = :makeTake WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userID', $userID);
            $statement->bindParam(':makeTake', $makeTake);

            $statement->execute();
        }

        /**
         * This method alters a premium user discount.
         * @param $useID
         * @param $premPerc
         * @return void
         */
        function setPremPercentage($useID, $premPerc)
        {
            $sql = "UPDATE users SET prem_percent = :premPerc WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':userID', $useID);
            $statement->bindParam(':premPerc', $premPerc);

            $statement->execute();
        }

        /**
         * This function updates the user's first name.
         * @param $userID
         * @param $updateVal
         * @return void
         */
        function updateUserFname($userID, $updateVal)
        {
            $sql = "UPDATE users SET f_name = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }

        /**
         * This function updates the user's last name.
         * @param $userID
         * @param $updateVal
         * @return void
         */
        function updateUserLname($userID, $updateVal)
        {
            $sql = "UPDATE users SET l_name = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }

        /**
         * This function updates the user's email.
         * @param $userID
         * @param $updateVal
         * @return void
         */
        function updateUserEmail($userID, $updateVal)
        {
            $sql = "UPDATE users SET user_email = :updateval WHERE user_id = :userID";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(':updateval', $updateVal);
            $statement->bindParam(':userID', $userID);

            $statement->execute();
        }

        /**
         * This function updates the user's phone number.
         * @param $userID
         * @param $updateVal
         * @return void
         */
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

        /*MAKE ORDER STATEMENTS*/

        /* $GLOBALS['dataLayer']->setNewOrders($_SESSION['loggedUser']->getUserID()); */
        /* $someVar = $GLOBALS['dataLayer']->getLatestOrderId(); */
        /* $GLOBALS['dataLayer']->insertOrderItems( $_SESSION['loggedUser']->getUserID(), $itemID, $itemQTY ) */

        // this will get you user id
        /* $_SESSION['loggedUser']->getUserID() */
        /**
         * This function inserts a new order into the orders table. User ID could be null if there is no loggedUser,
         * which is allowable by the orders table.
         * @param $userID
         * @return void
         */
        function setNewOrder($userID)
        {
            $sql = "INSERT INTO orders (user_id, is_fulfilled) VALUES (:userid, 0)";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(":userid", $userID);
            $statement->execute();
        }

        /**
         * This function returns the latest orderID.
         * @return number of latest order in table
         */
        function getLatestOrderId()
        {
            $sql = "SELECT MAX(order_id) FROM orders";
            $statement = $this->_dbh->prepare($sql);
            $statement->execute();
            return $statement->fetchColumn();
        }

        /**
         * This function inserts an order item with its quantity, based on an order id provided.
         * Note: needs to be used in a for-loop to populate all items from shipping cart, using the getLatestOrderId
         * function to know what to reference.
         * @return void
         */
        function insertOrderItems($orderID, $itemID, $itemQTY)
        {
            $sql = "INSERT INTO order_items (order_id, item_id, buy_qty) VALUES (:orderID, :itemID, :buyQTY)";
            $statement = $this->_dbh->prepare($sql);
            $statement->bindParam(":orderID", $orderID);
            $statement->bindParam(":itemID", $itemID);
            $statement->bindParam(":buyQTY", $itemQTY);
            $statement->execute();
        }

    }