<?php

    //This class file creates the Cart object.
class Cart
{
    //NOTE: no need to declare types in PHP.
    private $_inCartArr;
    /*private $_testField;*/

    /**
     * default constructor, don't believe this needs anything to initialize the cart
     */
    public function __construct()
    {
        $this->_inCartArr = array();
    }

    /**
     * Returns an array of keys in cartarr.
     * @return array
     */
    public function getInCartArr(): array
    {
        return array_keys($this->_inCartArr);
    }

    /**
     * Returns a qty of item based on product id from cart arr.
     * @param $productID
     * @return mixed
     */
    public function getProductQuantity($productID) {
        return $this->_inCartArr[$productID];
    }

    /**
     * This sets in the inCartArr array.
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

    /**
     *
     *Pushes an item to the cart array containing product ids
     */
    public function incrementCartItem($productID) {

        $this->_inCartArr[$productID] += 1;

        //just for testing purposes, went through a lot just to get the object to populate
        /*$this->_testField = $productID;*/
    }

    /**
     *
     *Removes an item from the cart array containing product ids
     */
    public function decrementCartItem($productID) {
        $this->_inCartArr[$productID] -= 1;

        //just for testing purposes, went through a lot just to get the object to populate
        /*$this->_testField = $productID;*/
    }

    /**
     *
     *Removes an item from the cart array containing product ids
     */
    public function removeCartItem($productID) {
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