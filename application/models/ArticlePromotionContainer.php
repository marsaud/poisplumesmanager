<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticlePromotionContainer
 *
 * @author MAKRIS
 */
final class ArticlePromotionContainer implements ArrayAccess, Iterator, Countable
{

    /**
     *
     * @var Promotion[]
     */
    private $_promos;

    public function __construct()
    {
        $this->_promos = array();
    }
    
    /**
     * 
     * @param mixed $offset
     * 
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_promos);
    }

    /**
     * 
     * @param mixed $offset
     * 
     * @return Promotion
     */
    public function offsetGet($offset)
    {
        return $this->_promos[$offset];
    }

    /**
     * 
     * @param mixed $offset Only NULL accepted.
     * @param Promotion $value
     * 
     * @return void
     * 
     * @throws LogicException
     */
    public function offsetSet($offset, $value)
    {
        if ($offset !== NULL)
        {
            throw new LogicException(
                    'ArticlePromotionContainer manages keys automatically. '
                    . 'Give no offset.'
            );
        }

        $this->_addItem($value);
    }

    /**
     * 
     * @param Promotion $promo
     * 
     * @return void
     */
    private function _addItem(Promotion $promo)
    {
        $this->_promos[$promo->id] = $promo;
    }

    /**
     * 
     * @param Promotion|mixed $offset
     * 
     * @return void
     * 
     * @throws OutOfRangeException
     */
    public function offsetUnset($offset)
    {
        if ($offset instanceof Promotion)
        {
            if ($this->offsetExists($offset->id)
                    && $this->offsetGet($offset->id === $offset))
            {
                unset($this->_promos[$offset->id]);
                return;
            }
        }
        else
        {
            if ($this->offsetExists($offset))
            {
                unset($this->_promos[$offset]);
                return;
            }
        }

        throw new OutOfRangeException('Item to remove has not be found');
    }

    /**
     * 
     * @return Promotion
     */
    public function current()
    {
        return current($this->_promos);
    }

    /**
     * 
     * @return integer
     */
    public function key()
    {
        return key($this->_promos);
    }

    /**
     * @return void
     */
    public function next()
    {
        next($this->_promos);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        reset($this->_promos);
    }

    /**
     * 
     * @return boolean
     */
    public function valid()
    {
        /**
         * Valid due to set encapsulated rules.
         */
        return $this->key() !== NULL;
    }

    public function count()
    {
        return count($this->_promos);
    }

}
