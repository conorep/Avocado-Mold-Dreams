<?php

    /**
     * User class for premium users of AMD site.
     */
    class AmdPremUser extends AMDUser
    {
        //premium member fields
        private $_premStatus;
        private $_premColor;

        /**
         * This is the constructor for the premium member class.
         * @param $_userID
         * @param $_isAdmin
         * @param $_email
         * @param $_phoneNum
         * @param $_fname
         * @param $_lname
         * @param $_hashedpass
         * @param $_premStatus
         * @param $_premColor
         */
        public function __construct($_userID, $_isAdmin, $_email, $_phoneNum, $_fname, $_lname, $_hashedpass, $_premStatus, $_premColor)
        {
            parent::__construct($_userID, $_isAdmin, $_email, $_phoneNum, $_fname, $_lname, $_hashedpass);
            $this->_premStatus = $_premStatus;
            $this->_premColor = $_premColor;
        }

        /**
         * This function returns a number stating the discount % that this user gets.
         * @return number
         */
        public function getPremStatus()
        {
            return $this->_premStatus;
        }

        /**
         * This function sets the premStatus percentage.
         * @param number $premStatus
         */
        public function setPremStatus($premStatus)
        {
            $this->_premStatus = $premStatus;
        }

        /**
         * This function returns a color that the customer panel's text will display as.
         * @return string
         */
        public function getPremColor()
        {
            return $this->_premColor;
        }

        /**
         * This function sets the premium color string.
         * @param string $premColor
         */
        public function setPremColor($premColor)
        {
            $this->_premColor = $premColor;
        }




    }
