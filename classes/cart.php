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
     *
     */
    public function __construct()
    {

    }

    /**
     * Get items in  cart.
     *
     * @return array
     */
    public function getItems()
    {
        return $this->_inCartArr;
    }

    /**
     *
     *Adds
     */

    public function addToCart(int $productID)
    {
        $this->_inCartArr[$productID] = 1;
    }

    /**
     * Get the total of item in cart.
     *
     * @return int
     */
    public function getTotalItem()
    {
        $total = 0;

        foreach ($this->_inCartArr as $items) {
            foreach ($items as $item) {
                $total++;
            }
        }

        return $total;
    }

    /**
     * Remove all items from cart.
     */
    public function clear()
    {
        $this->_inCartArr = [];
    }

}