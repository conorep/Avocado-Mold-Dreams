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
            $views = new Template();
            echo $views->render('views/home.html');
        }
    }