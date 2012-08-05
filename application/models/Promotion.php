<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Promotion
 *
 * @author fabrice
 */
class Promotion
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

    /**
     *
     * @param float $price
     *
     * @return float
     */
    public function apply($price)
    {
        return $price * (1 + ($this->ratio / 100));
    }

    /**
     *
     * @param float $price
     *
     * @return float
     */
    public function remove($price)
    {
        return $price / (1 + ($this->ratio / 100));
    }
}