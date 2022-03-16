<?php

    /**
     * User class for standard users of AMD site.
     */
    class AMDUser
    {
        // standard member fields
        private $_userID;
        private $_isAdmin;
        private $_email;
        private $_phoneNum;
        private $_fname;
        private $_lname;
        private $_hashedpass;

        /**
         * Constructor for the standard user class.
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
         * This method returns the user's ID.
         * @return number userID
         */
        public function getUserID()
        {
            return $this->_userID;
        }

        /**
         * This method states if the user is an admin or not.
         * @return number 0 for non-admin, 1 for admin.
         */
        public function getIsAdmin()
        {
            return $this->_isAdmin;
        }

        /**
         * This method returns an email
         * @return String
         */
        public function getEmail()
        {
            return $this->_email;
        }

        /**
         * @return String
         */
        public function getPhoneNum()
        {
            return $this->_phoneNum;
        }

        /**
         * @return String
         */
        public function getFname()
        {
            return $this->_fname;
        }

        /**
         * @return String
         */
        public function getLname()
        {
            return $this->_lname;
        }

        /**
         * @param number $isAdmin
         */
        public function setIsAdmin($isAdmin)
        {
            $this->_isAdmin = $isAdmin;
        }

        /**
         * @param String $email
         */
        public function setEmail($email)
        {
            $this->_email = $email;
        }

        /**
         * @param String $phoneNum
         */
        public function setPhoneNum($phoneNum)
        {
            $this->_phoneNum = $phoneNum;
        }

        /**
         * @param String $fname
         */
        public function setFname($fname)
        {
            $this->_fname = $fname;
        }

        /**
         * @param String $lname
         */
        public function setLname($lname)
        {
            $this->_lname = $lname;
        }

    }
