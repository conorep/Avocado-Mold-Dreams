<?php

    //This class file creates the Cart object.
class Cart
{
    //NOTE: no need to declare types in PHP.
    public $_inCartArr;
    public $_testField;

    /**
     * default constructor, don't believe this needs anything to initialize the cart
     */
    public function __construct()
    {
        $this->_inCartArr = array();
    }

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

    /**
     * This function checks for an empty cart array. If empty, cart array is not filled when visiting cart page.
     * @return bool
     */
    public function checkEmptyCartArr()
    {
        if(empty($_inCartArr)){
            return true;
        } else {
            return false;
        }
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

    /**
     *
     * Returns quantity of the associated product by ID
     */
    public function getVal($productID) {
        return $this->_inCartArr[$productID];
    }

}