<?php

class Stock_IndexController extends AbstractControllerAbstract
{

    public function init()
    {
        require_once APPLICATION_PATH . '/modules/stock/models/StockManager.php';

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        /* @var $ajaxContext Zend_Controller_Action_Helper_AjaxContext */
        $ajaxContext->addActionContext('update', 'json')
                ->initContext();
    }

    public function indexAction()
    {
        $selectedCategory = (!empty($_POST['categoryfilter']) ?
                        $_POST['categoryfilter'] : NULL);

        $this->view->categoryTree = $this->categoryMapper->getCategoryTree();

        $this->view->articleList = $this->articleMapper->getArticles(
                $selectedCategory, true
        );

        $this->view->selectedCategory = $selectedCategory;
    }

    public function updateAction()
    {
        $request = $this->getRequest();
        /* @var $request Zend_Controller_Request_Http */
        if (!$request->isXmlHttpRequest())
        {
            throw new RuntimeException('Wrong Request Context');
        }

        $reference = $request->getParam('ref');
        $comment = $request->getParam('cmnt');
        $quantity = $request->getParam('qty');

        $stockManager = new StockManager($this->db);
        $this->db->beginTransaction();
        try
        {
            $stockManager->update($reference, $quantity, $comment);
            $this->db->commit();
        }
        catch (Exception $exc)
        {
            $this->db->rollBack();
            throw $exc;
        }


        $article = $this->articleMapper->find($reference);

        $this->view->reference = $article->reference;
        $this->view->quantity = $article->quantity;
        $this->view->unit = $article->unit;
    }

}
