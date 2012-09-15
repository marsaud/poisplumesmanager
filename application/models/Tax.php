<?php

/**
 *
 */

/**
 * Description of Tax
 *
 * @author fabrice
 */
class Tax
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var float
     */
    public $ratio;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string;
     */
    public $description;

    public function evaluate($price)
    {
        return $price * $this->ratio / 100;
    }
    
    /**
     *
     * @param float $price
     *
     * @return float
     */
    public function apply($price)
    {
        return $price * (1 + $this->ratio / 100);
    }

    /**
     *
     * @param float $price
     * 
     * @return float
     */
    public function remove($price)
    {
        return $price / (1 + $this->ratio / 100);
    }

}