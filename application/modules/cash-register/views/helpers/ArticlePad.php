<?php
/**
 *
 */

/**
 * Description of CashRegister_View_Helper_ArticlePad
 *
 * @author fabrice
 */
class CashRegister_View_Helper_ArticlePad extends Zend_View_Helper_Abstract
{

    /**
     *
     * @param Article[] $articles
     *
     * @return string
     */
    public function articlePad(array $articles)
    {
        $articlePad = '';

        foreach ($articles as $article)
        {
            $articlePad .= $this->_renderArticleForPad($article);
        }

        return $articlePad;
    }

    protected function _renderArticleForPad(Article $article)
    {
        /* @var $promo Promotion */
        $promo = $article->onePromo;
        ob_start();
        ?>
        <div class="article button"
             ref="<?php echo $article->reference; ?>"
             name="<?php echo $article->name; ?>"
             promoratio="<?php echo $promo ? $promo->ratio : ''; ?>"
             saleprice="<?php echo $article->getFinalPrice(); ?>">
            <div class="facevalue">
                <?php echo $article->name; ?><br/>
                <?php echo $promo ? $promo->ratio . '%' : ''; ?><br />
                <?php echo $this->view->currency($article->getFinalPrice()); ?>
            </div>
            <div class="inputdata">
                <input type="hidden" class="qty"
                       name="<?php echo $article->reference; ?>" value="0"/>
                <input type="hidden" class="promoid"
                       name="promo_<?php echo $article->reference; ?>"
                       value="<?php echo $promo ? $promo->id : ''; ?>"/>
            </div>
        </div>
        <?php
        $output = ob_get_clean();
        return $output;
    }

}