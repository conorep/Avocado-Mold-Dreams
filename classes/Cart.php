<?php

class Cart
{
    private array $_inCartArr;




//
/*
 $age=array("Peter"=>"35","Ben"=>"37","Joe"=>"43");
 foreach($age as $x=>$x_value)
{
echo "Key=" . $x . ", Value=" . $x_value;
echo "<br>";
}
*/
    /**
     * default constructor, don't believe this needs anything to initialize the cart
     */
    public function __construct()
    {

    }

    /**
     *
     *Adds
     */

    public function addToCart(int $productID) {
        $this->_inCartArr[$productID] = 1;
    }

}