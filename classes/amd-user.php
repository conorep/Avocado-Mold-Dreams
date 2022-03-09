<?php

    /**
     * User class for standard users of dating site.
     */
    class AMDUser
    {
        private $_userID;
        private $_isAdmin;
        private $_email;
        private $_phoneNum;
        private $_fname;
        private $_lname;
        private $_hashedpass;

        /**
         * @param $_userID
         * @param $_isAdmin
         * @param $_email
         * @param $_phoneNum
         * @param $_fname
         * @param $_lname
         * @param $_hashedpass
         */
        public function __construct($_userID, $_isAdmin, $_email, $_phoneNum, $_fname, $_lname, $_hashedpass)
        {
            $this->_userID = $_userID;
            $this->_isAdmin = $_isAdmin;
            $this->_email = $_email;
            $this->_phoneNum = $_phoneNum;
            $this->_fname = $_fname;
            $this->_lname = $_lname;
            $this->_hashedpass = $_hashedpass;
        }

        /**
         * @return mixed
         */
        public function getUserID()
        {
            return $this->_userID;
        }

        /**
         * @return mixed
         */
        public function getIsAdmin()
        {
            return $this->_isAdmin;
        }

        /**
         * @return mixed
         */
        public function getEmail()
        {
            return $this->_email;
        }

        /**
         * @return mixed
         */
        public function getPhoneNum()
        {
            return $this->_phoneNum;
        }

        /**
         * @return mixed
         */
        public function getFname()
        {
            return $this->_fname;
        }

        /**
         * @return mixed
         */
        public function getLname()
        {
            return $this->_lname;
        }

        /**
         * @param mixed $isAdmin
         */
        public function setIsAdmin($isAdmin)
        {
            $this->_isAdmin = $isAdmin;
        }

        /**
         * @param mixed $email
         */
        public function setEmail($email)
        {
            $this->_email = $email;
        }

        /**
         * @param mixed $phoneNum
         */
        public function setPhoneNum($phoneNum)
        {
            $this->_phoneNum = $phoneNum;
        }

        /**
         * @param mixed $fname
         */
        public function setFname($fname)
        {
            $this->_fname = $fname;
        }

        /**
         * @param mixed $lname
         */
        public function setLname($lname)
        {
            $this->_lname = $lname;
        }

    }
