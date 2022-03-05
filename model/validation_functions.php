<?php

    //TODO: MOST OF THIS PAGE
    class ValidationFunctions
    {
        /*this is a placeholder validator*/
        static function validUserName($user)
        {
            return strlen($user) >= 3;
        }

        /*this is a placeholder validator*/
        static function validPassword($pass)
        {
            return strlen($pass) >= 3;
        }

        static function validPhone()
        {

        }

        /*TODO: probably need en email validation function (not just checking DB for existence), plus validating
            names,possibly password content, etc.*/

    }
