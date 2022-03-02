<?php

    //328/Avocado-Mold-Dreams/controller/controller.php
    class Controller
    {
        private $_f3; //f3 object

        function __construct($f3)
        {
            $this->_f3 = $f3;
        }

        function home()
        {
            $rows = $GLOBALS['dataLayer']->getItems();
            $this->_f3->set('amd_products', $rows);

            $views = new Template();
            echo $views->render('views/home.html');
        }

        function admin()
        {
            //should I make a sub method for home and admin to use...?
            $rows = $GLOBALS['dataLayer']->getItems();
            $this->_f3->set('amdProducts', $rows);

            //user questions stuff for table display
            $rows = $GLOBALS['dataLayer']->getUserQuestions();
            $this->_f3->set('questionStuff', $rows);

            $views = new Template();
            echo $views->render('views/admin.html');
        }
    }