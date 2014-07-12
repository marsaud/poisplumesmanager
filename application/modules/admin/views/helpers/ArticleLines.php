<?php
/**
 *
 */

/**
 * Description of Admin_View_Helper_ArticleLines
 *
 * @author fabrice
 */
class Admin_View_Helper_ArticleLines extends Zend_View_Helper_Abstract
{

    /**
     *
     * @param Article[] $articles
     *
     * @return string
     */
    public function articleLines(array $articles)
    {
        ob_start();

        foreach ($articles as $article):
            ?>
            <tr>
                <td><?php echo $article->reference; ?></td>
                <td><?php echo $article->name; ?></td>
                <td><?php echo $article->description; ?></td>
                <td><?php echo $this->_categoryList($article); ?></td>
                <td><?php echo $this->view->currency($article->getSalePrice()); ?></td>
                <td><?php echo $article->tax->ratio; ?>%</td>
                <td><?php echo $this->_promoList($article); ?></td>
                <td><?php echo $this->view->currency($article->getFinalPrice()); ?></td>
                <td><?php echo ($article->provider !== NULL ? $article->provider->name : ''); ?></td>
            </tr>
            <?php
        endforeach;

        return ob_get_clean();
    }

    protected function _categoryList(Article $article)
    {
        ob_start();
        ?>
        <ul>
            <?php
            foreach ($article->categories as $category):
                ?>
                <li><?php echo $category->name; ?></li>
                <?php
            endforeach;
            ?>
        </ul>
        <?php
        return ob_get_clean();
    }

    protected function _promoList(Article $article)
    {
        ob_start();
        ?>
        <ul>
            <?php
            foreach ($article->promos as $promo):
                ?>
                <li><?php echo $promo->name . ' : ' . $promo->ratio; ?>%</li>
                <?php
            endforeach;
            ?>
        </ul>
        <?php
        return ob_get_clean();
    }

}
