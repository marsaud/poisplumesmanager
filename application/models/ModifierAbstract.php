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

    public function evaluate($rawPrice)
    {
        return round(($rawPrice * $this->ratio / 100), 2);
    }

    /**
     *
     * @param float $rawPrice
     *
     * @return float
     */
    public function apply($rawPrice)
    {
        return $rawPrice + $this->evaluate($rawPrice);
    }

    /**
     *
     * @param float $modifiedPrice
     *
     * @return float
     */
    public function remove($modifiedPrice)
    {
        return round($modifiedPrice / (1 + ($this->ratio / 100)), 2);
    }

}
