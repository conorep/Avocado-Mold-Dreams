<?php

class Cart
{
    public ?array $_inCartArr;
    public int $_testField;
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
        $this->_inCartArr = array();
    }

    /**
     *
     *Pushes an item to the cart array containing product ids
     */

    public function addToCart(int $productID) {
        array_push($this->_inCartArr, $productID);

        //just for testing purposes, went through a lot just to get the object to populate
        $this->_testField = $productID;
    }

}