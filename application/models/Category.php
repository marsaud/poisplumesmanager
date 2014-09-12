<?php

/**
 *
 */

/**
 *
 *
 * @author fabrice
 *
 * @property-read Category[] $subCategories
 * @property string $reference
 * @property string $name
 * @property string $description
 */
class Category extends PAF_Object_Base implements ArrayAccess, IteratorAggregate, Countable
{

    /**
     *
     * @var string
     */
    protected $_reference;

    /**
     *
     * @var string
     */
    protected $_name;

    /**
     *
     * @var string
     */
    protected $_description;

    /**
     *
     * @var ArrayObject Category[]
     */
    protected $_subCategories;

    public function __construct()
    {
        parent::__construct();
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

    public function count()
    {
        return $this->_subCategories->count();
    }

    protected function _initProperties()
    {
        $this->_extendReadProperties(array(
            'subCategories' => 'subCategories'
        ));

        $this->_extendProperties(array(
            'name' => 'name',
            'description' => 'description'
        ));
    }

    protected function _getSubCategories()
    {
        return $this->_subCategories->getArrayCopy();
    }

    protected function _setReference($reference)
    {
        ModelAssertions::ref($reference, 'Category reference');
        $this->_reference = $reference;
    }

    protected function _setName($name)
    {
        ModelAssertions::notEmptyStr($name, 'Category name');
        $this->_name = $name;
    }

}
