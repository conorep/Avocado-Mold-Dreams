<?php

    /*This class handles data validation from view/controller input.*/
    class ValidationFunctions
    {

        static function validUserName($user)
        {
            return strlen($user) >= 7;
        }

        static function validPassword($pass)
        {
            return strlen($pass) >= 3;
        }

        /**
         * break phone num down to numbers only and return it
         * @param $phoneNum String of phone num to break down
         * @return string phone number with only nums
         */
        static function stripPhone($phoneNum)
        {
            $phoneNumeric = preg_replace('/[^0-9]/', '', $phoneNum);

            return $phoneNumeric;
        }

        /**
         * check for valid us number
         * @param $phonenum String of numbers to check for 10 characters and numberic
         * @return bool true if valid, otherwise false
         */
        static function validPhone($phonenum)
        {
            //needs to be 10 numbers long. nothing else.
            if (strlen($phonenum) != 10 || !is_numeric($phonenum)) {
                return false;
            }
            return true;
        }

        /**
         * check for valid email
         * @param $email String input to check
         * @return mixed returning true or false if it's a real email address or not
         */
        static function validEmail($email)
        {
            return (filter_var($email, FILTER_VALIDATE_EMAIL));
        }

    }
