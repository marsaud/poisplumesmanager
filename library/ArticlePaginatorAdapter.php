<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ArticlePaginatorAdapter
 *
 * @author fabrice
 */
class ArticlePaginatorAdapter implements Zend_Paginator_Adapter_Interface
{

    /**
     *
     * @var ArticleMapper
     */
    protected $_mapper;

    /**
     *
     * @var 
     */
    protected $_items;

    public function __construct(ArticleMapper $mapper)
    {
        $this->_mapper = $mapper;
    }

    public function count()
    {
        return $this->_mapper->count();
    }

    public function getItems($offset, $itemCountPerPage)
    {
        return $this->_mapper->getArticles(
                        ArticleMapper::ALL_CATEGORIES, ArticleMapper::ALL_ARTICLES, $itemCountPerPage, $offset
        );
    }

}
