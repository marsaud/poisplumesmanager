<?php

/**
 *
 */

/**
 * Description of Category
 *
 * @author fabrice
 */
class Category implements ArrayAccess, IteratorAggregate
{

    /**
     *
     * @var string
     */
    public $reference;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var Category[]
     */
    public $subCategories;

    public function __construct()
    {
        $this->subCategories = new ArrayObject(array());
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->subCategories);
    }

    public function offsetGet($offset)
    {
        return $this->subCategories[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if ($offset !== NULL)
        {
            throw new RuntimeException();
        }
        else
        {
            if ($value instanceof Category)
            {
                $this->subCategories[$value->reference] = $value;
            }
            else
            {
                throw new RuntimeException();
            }
        }
    }

    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->subCategories))
        {
            unset($this->subCategories[$offset]);
        }
        else
        {
            throw new RuntimeException();
        }
    }

    public function getIterator()
    {
        return $this->subCategories;
    }

}