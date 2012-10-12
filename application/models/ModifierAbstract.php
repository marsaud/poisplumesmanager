<?php

/**
 *
 */

/**
 * Description of ModifierAbstract
 *
 * @author fabrice
 */
abstract class ModifierAbstract
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
        return round(($price * $this->ratio / 100), 2);
    }
    
    /**
     *
     * @param float $price
     *
     * @return float
     */
    public function apply($price)
    {
        return $price + $this->evaluate($price);
    }
    
    /**
     *
     * @param float $price
     *
     * @return float
     */
    public function remove($price)
    {
        return round($price / (1 + ($this->ratio / 100)), 2);
    }

}