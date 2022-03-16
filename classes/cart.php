<?php

class cart
{
    //NOTE: no need to declare types in PHP.
    public $_inCartArr;
    public $_testField;

    /**
     * @return array
     */
    public function getInCartArr(): array
    {
        return array_keys($this->_inCartArr);
//        return $this->_inCartArr;
    }

    public function getProductQuantity($productID) {
        return $this->_inCartArr[$productID];
    }

    /**
     * @param array $inCartArr
     */
    public function setInCartArr(array $inCartArr): void
    {
        $this->_inCartArr = $inCartArr;
    }
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
    public function incrementCartItem($productID) {
//        array_push($this->_inCartArr, $productID);

        $this->_inCartArr[$productID] += 1;

        //just for testing purposes, went through a lot just to get the object to populate
        $this->_testField = $productID;
    }

    /**
     *
     *Removes an item from the cart array containing product ids
     */
    public function decrementCartItem($productID) {
//        array_push($this->_inCartArr, $productID);

        $this->_inCartArr[$productID] -= 1;

        //just for testing purposes, went through a lot just to get the object to populate
        $this->_testField = $productID;
    }

    /**
     *
     *Removes an item from the cart array containing product ids
     */
    public function removeCartItem($productID) {
//        array_push($this->_inCartArr, $productID);
        unset($this->_inCartArr[$productID]);
//        $this->_inCartArr[$productID] -= 1;

        //just for testing purposes, went through a lot just to get the object to populate
//        $this->_testField = $productID;
    }

}