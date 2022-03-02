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


    }