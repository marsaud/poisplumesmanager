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
     * @param integer $offset
     * 
     * @return boolean
     */
    public function offsetExists($offset)
    {
        $offset = (integer) $offset;
        return array_key_exists($offset, $this->_promos);
    }

    /**
     * 
     * @param integer $offset
     * 
     * @return Promotion
     */
    public function offsetGet($offset)
    {
        $offset = (integer) $offset;
        return $this->_promos[$offset];
    }

    /**
     * 
     * @param NULL $offset Only NULL accepted.
     * @param Promotion $value
     * 
     * @return void
     * 
     * @throws LogicException
     */
    public function offsetSet($offset, $value)
    {
        if (NULL !== $offset)
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
        $id = (integer) $promo->id;
        $this->_promos[$id] = $promo;
    }

    /**
     * 
     * @param Promotion|integer $offset
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
                    && $this->offsetGet($offset->id) === $offset)
            {
                $id = (integer) $offset->id;
                unset($this->_promos[$id]);
                return;
            }
        }
        else
        {
            $offset = (integer) $offset;
            if ($this->offsetExists($offset))
            {
                unset($this->_promos[$offset]);
                return;
            }
        }

        throw new OutOfRangeException('Item to remove was not found');
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
        return (NULL !== $this->key());
    }

    public function count()
    {
        return count($this->_promos);
    }

}
