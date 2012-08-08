<?php

/**
 *
 */

/**
 * Description of Category
 *
 * @author fabrice
 *
 * @property-read Category[] $subCategories Description
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
    protected $_subCategories;

    public function __construct()
    {
        $this->_subCategories = new ArrayObject(array());
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_subCategories);
    }

    public function offsetGet($offset)
    {
        return $this->_subCategories[$offset];
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
                $this->_subCategories[$value->reference] = $value;
            }
            else
            {
                throw new RuntimeException();
            }
        }
    }

    public function offsetUnset($offset)
    {
        if (array_key_exists($offset, $this->_subCategories))
        {
            unset($this->_subCategories[$offset]);
        }
        else
        {
            throw new RuntimeException();
        }
    }

    public function getIterator()
    {
        return $this->_subCategories->getIterator();
    }

    public function __get($name)
    {
        if ($name == 'subCategories')
        {
            return $this->_subCategories->getArrayCopy();
        }
        else
        {
            throw new OutOfRangeException();
        }
    }

    public function __set($name, $value)
    {
        throw new OutOfRangeException();
    }


}